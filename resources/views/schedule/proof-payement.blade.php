@extends('templates.app')
@section('content')
<div class="container card w-75 d-block mx-auto text-center mt-4 p-4">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
       <a href="{{ route('tickets.export_pdf', $ticket->id) }}" class="btn btn-secondary">Download PDF</a>
        </div>
        <b>{{ $ticket['schedule']['cinema']['name'] }}</b>
        <h5 class="m-0">Studio</h5>
        <hr>
        <div class="d-flex flex-wrap justify-content-center">
            @foreach(json_decode($ticket['row_of_seat'], true) as $item)
                <div class="my-3 mx-4 card p-3" style="width: 200px">
                    <p class="ticket-title mb-1">{{ $ticket['schedule']['movie']['title'] }}</p>
                    <div class="ticket-details text-start">
                        <small>Tanggal:</small>
                        {{ \Carbon\Carbon::parse($ticket['ticketPayment']['booked_date'])->format('d F Y') }} <br>

                        <small>Waktu:</small>
                        {{ \Carbon\Carbon::parse($ticket['hours'])->format('H:i') }} <br>

                        <small>Kursi:</small>
                        {{ $item }} <br>

                        <small>Harga:</small>
                        Rp. {{ number_format($ticket['schedule']['price']) }}
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
