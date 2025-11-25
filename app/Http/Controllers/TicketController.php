<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketPayment;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketActive = Ticket::whereHas('ticketPayment', function($q) {
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', '>=', $date);
        })->where('user_id', auth()->id())->get();
        $ticketNonActive = Ticket::whereHas('ticketPayment', function($q) {
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', '<', $date);
        })->where('user_id', auth()->id())->get();
        return view('ticket.index', compact('ticketActive', 'ticketNonActive'));
    }

    public function chart()
    {
        $tickets = Ticket::whereHas('ticketPayment', function($q) {
            $q->where('paid_date', '<>', NULL);
        })->get()->groupBy(function($ticket) {
            return \Carbon\Carbon::parse($ticket->ticketPayment->paid_date)->format('Y-m-d');
        })->toArray();
        $labels = array_keys($tickets);
        $data = [];
        foreach ($tickets as $value) {
            array_push($data, count($value));
        }
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
        dd($tickets);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'row_of_seat' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'tax' => 'required',
            'hour' => 'required',
        ]);
        $createData = Ticket::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'row_of_seat' => json_encode($request->row_of_seat),
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'tax' => $request->tax,
            'hour' => $request->hour,
            'date' => now(),
            'actived' => 0,
        ]);
        return response()->json(['message' => 'Ticket created successfully', 'data' => $createData]);
    }

    public function orderPage($ticketId)
    {
       $ticket = Ticket::where('id', $ticketId)
    ->with(['schedule', 'schedule.cinema', 'schedule.movie'])
    ->first();

$promos = Promo::where('active', 1)->get();
return view('schedule.order', compact('ticket', 'promos'));

    }

    public function createQrcode(request $request)
    {
        $request->validate([
            'ticket_id' => 'required',

        ]);

        $ticket = Ticket::find($request['ticket_id']);
        $kodeQr = 'TICKET' . $ticket['id'];

        $qrcode = Qrcode::format('svg') ->size(30)->margin(2)->generate($kodeQr);

        $filename = 'qrcode' . '.svg';
        $folder = 'qrcode/' . $filename;
        Storage::disk('public')->put($folder, $qrcode);

        $createData = TicketPayment::create([
            'ticket_id' => $ticket['id'],
            'qrcode' => $folder,
            'booked_date' => now(),
            'status' => 'process',
        ]);

        // dd($request->promo_id);

        if ($request->promo_id != null) {
            $promo = Promo::find($request->promo_id);
            if ($promo['type'] == 'percent') {
                $discount = $ticket['total_price'] * ($promo['discount'] / 100);
            } else {
                $discount = $promo['discount'];
            }
            $newTotalPrice = $ticket['total_price'] - $discount;
            $ticket->update(['total_price' => $newTotalPrice,
            'promo_id' => $request->promo_id
            ]);
        }
        return response()->json([
            'message' => 'Qrcode created successfully',
            'data' => $ticket
        ]);
    }

    public function paymentPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['ticketPayment', 'promo'])->first();
        // dd($ticket);
        return view('schedule.payment', compact('ticket'));
    }

    public function updateStatusPayment(Request $request, $ticketId)
    {
       $updateData = Ticketpayment::where('ticket_id', $ticketId)->update([
        'status' => 'paid-off',
        'paid_date' => now()
       ]);
       if ($updateData) {
        Ticket::where('id', $ticketId)->update([
            'actived' => 1,
        ]);
       }
         return redirect()->route('tickets.payment.proof', $ticketId);
    }

    public function proofPayment($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie', 'promo', 'ticketPayment'])->first();
        return view('schedule.proof-payement', compact('ticket'));
    }

    public function exportPdf($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie', 'promo', 'ticketPayment'])->first()->toArray();

        view()->share('ticket', $ticket);
        $pdf = PDF::loadView('schedule.export-pdf', $ticket);
        $fileName = 'TICKET' . $ticket['id'] . '.pdf';
        return $pdf->download($fileName);
    }
    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}

