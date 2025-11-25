<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
use App\Models\Cinema;
use App\Models\Movie;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Ticket;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        $movies = Movie::all();

        $schedules = Schedule::with(['cinema', 'movie'])->get();

        return view('staff.schedule.index' , compact('cinemas', 'movies', 'schedules'));
    }

    public function datatables()
    {
        $schedules = Schedule::with(['cinema', 'movie'])->select('schedules.*');

        return DataTables::of($schedules)
            ->addIndexColumn()
            ->addColumn('hours', function ($schedule) {
                $hours = is_array($schedule->hours) ? $schedule->hours : json_decode($schedule->hours, true);
                return implode(', ', $hours);
            })
            ->addColumn('cinema_id', function ($schedule) {
                return $schedule->cinema->name;
            })
            ->addColumn('movie_id', function ($schedule) {
                return $schedule->movie->title;
            })
            ->addColumn('actions', function ($schedule) {
                $btnEdit = '<a href="' . route('staff.schedules.edit', ['id' => $schedule->id]) . '" class="btn btn-primary me-2">Edit</a>';

                $btnDelete = '<form action="' . route('staff.schedules.delete', ['id' => $schedule->id]) . '" method="POST" style="display:inline;">'
                   . '<input type="hidden" name="_token" value="' . csrf_field() . method_field('DELETE') . ''
                    . '<input type="hidden" name="_method" value="DELETE">'
                    . '<button class="btn btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>'
                    . '</form>';

                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['actions', 'hours', 'cinema_id', 'movie_id'])
            ->make(true);
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
        'cinema_id' => 'required',
        'movie_id' => 'required',
        'hours.*' => 'required|date_format:H:i',
        'price' => 'required|numeric',
    ], [
        'cinema_id.required' => 'Bioskop wajib diisi.',
        'cinema_id.exists' => 'Bioskop tidak valid.',
        'movie_id.required' => 'Film wajib diisi.',
        'movie_id.exists' => 'Film tidak valid.',
        'hours.*.required' => 'Jam tayang wajib diisi.',
        'hours.*.date_format' => 'Jam tayang Diisi dengan jam:menit.',
    ]);

    // Ambil data schedule jika sudah ada
    $schedule = Schedule::where('cinema_id', $request->cinema_id)
        ->where('movie_id', $request->movie_id)
        ->first();

   if ($schedule) {
    // Pastikan $hoursBefore adalah array
    $hoursBefore = is_array($schedule->hours) ? $schedule->hours : json_decode($schedule->hours, true) ?? [];
    $mergeHours = array_merge($hoursBefore, $request->hours);
    $newHours = array_unique($mergeHours);

    $schedule->update([
        'hours' => $newHours,
        'price' => $request->price,
    ]);
    $createdData = $schedule;
} else {
    // Data baru
    $newHours = array_unique($request->hours);
    $createdData = Schedule::create([
        'cinema_id' => $request->cinema_id,
        'movie_id' => $request->movie_id,
        'hours' => json_encode($newHours),
        'price' => $request->price,
    ]);
}

    if ($createdData) {
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil Menambahkan Data Baru!');
    } else {
        return redirect()->back()->with('error', 'Gagal! silakan coba lagi');
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule, $id)
    {
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, $id)
    {
        $request->validate([
            'hours.*' => 'required|date_format:H:i',
            'price' => 'required|numeric',
        ], [
            'hours.*.required' => 'Jam tayang wajib diisi.',
            'hours.*.date_format' => 'Jam tayang Diisi dengan jam:menit.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
        ]);

        $updateData = $schedule::where('id', $id)->update([
            'hours' => $request->hours,
            'price' => $request->price,
        ]);

        if ($updateData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil Mengubah Data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silakan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, $id)
    {
        Schedule::where('id', $id)->delete();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil Menghapus Data!');
    }
    public function trash()
    {
        $schedulesTrash = Schedule::with(['cinema', 'movie'])->onlyTrashed()->get();
        return view('staff.schedule.trash', compact('schedulesTrash'));
    }
    public function restore($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->restore();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil Mengembalikan Data!');
    }
    public function deletePermanent($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil Menghapus Data Secara Permanen!');
    }
    public function export()
    {
        return Excel::download(new ScheduleExport, 'jadwal_tayangan.xlsx');
    }

    public function showSeats($schedule_id, $hourId)
    {
        $schedule = Schedule::where('id', $schedule_id)->with(['cinema'])->first();
        // dd($schedule);
        $hour = json_decode($schedule['hours'])[$hourId];
        $seats = Ticket::where('schedule_id', $schedule_id)->whereHas('ticketPayment', function($q){
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', $date);
        })->whereTime('hour', $hour)->pluck('row_of_seat');
        // dd($seats);
        $seatsFormat = array_merge(json_decode(...$seats));
        // dd($seatsFormat);
        return view('schedule.show-seats', compact('schedule', 'hour', 'seatsFormat'));
    }
}
