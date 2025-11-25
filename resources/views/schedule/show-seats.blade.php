@extends('templates.app')

@section('content')
<div class="container-card my-5 p-4" style="margin-bottom: 10% !important;">
    <div class="card-body">
        <b>{{ $schedule['cinema']['name'] }}</b>
        <br>
        <b>{{ now()->format('d F, Y') }} - {{ $hour }}</b>
        <br>
        <div class="alert alert-secondary">
            <i class="fa-solid fa-info text-danger me-3"></i> Anak usia 2 tahun ke atas wajib memiliki tiket.
        </div>
        <div class="w-50 d-block mx-auto my-3">
        <div class="row">
            <div class="col-4 d-flex">
                <div style="background: #112646; width: 20px; height:20px;"></div>
                <span class="ms-2">Kursi Tersedia</span>
            </div>
            <div class="col-4 d-flex">
                <div style="background: blue; width: 20px; height :20px;"></div>
                <span class="ms-2">Kursi Dipilih</span>
            </div>
            <div class="col-4 d-flex">
                <div style="background: #eaeaea; width: 20px; height:20px;"></div>
                <span class="ms-2">Kursi Terisi</span>
            </div>
        </div>
        </div>
        @php
            $rows = range('A', 'H');
            $cols = range(1, 18);
        @endphp
        @foreach ($rows as $row)
            <div class="d-flex justify-content-center">
                @foreach ($cols as $col)
                @if ($col == 7)
                    <div style="width: 20px"></div>
                @endif
                @php
                    $seat = $row . '-' . $col;
                @endphp
                @if (in_array($seat, $seatsFormat))
                <div style="background:grey; color:black; width:45px; height:45px; margin:5px; border-radius: 8px; text-align:center; padding-top:12px;>
                    <small><b>{{ $row }}{{ $col }}</b></small>
                </div>
                @else
                <div style="background:#112646; color:white; width:45px; height:45px; margin:5px; border-radius: 8px; text-align:center; padding-top:12px; cursor: pointer; onclick="selectSeat({{ $schedule->price }}, '{{ $row }}', '{{ $col }}', this)">
                    <small><b>{{ $row }}{{ $col }}</b></small>
                </div>
                @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<div class="fixed-bottom">
    <div class="w-100 bg-light text-center px-3" style="border: 1px solid black">
        <b>Layar Bioskop</b>
    </div>
    <div class="row bg-light">
    <div class ="col-6 text-center p-3" style="border: 1px solid black">
        <b>Total Harga</b>
        <br>
        <b id="total-price">Rp. 0</b>
    </div>
    <div class ="col-6 text-center p-3" style="border: 1px solid black">
        <b>Kursi Dipilih</b>
        <br>
        <b id="selected-seats">-</b>
    </div>
</div>
<input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
<input type="hidden" name="schedule_id" id="schedule_id" value="{{ $schedule->id }}">
<input type="hidden" name="hour" id="hour" value="{{ $hour }}">
<div class="w-100 bg-light text-center py-3" style="font-weight: bold" id="btnCreateOrder">Ringkasan Order</div>
</div>
@endsection

@push('script')
<script>
    let seats = [];
    let totalPrice = 0;

    function selectSeat(price, row, col, element) {
        let seat = row + "-" + col;

        if (element.classList.contains('taken')) return;


        if (element.classList.contains('selected')) {
            element.classList.remove('selected');
            element.style.backgroundColor = '#112646';
            seats = seats.filter(s => s !== seat);
        } else {
            element.classList.add('selected');
            element.style.backgroundColor = 'blue';
            seats.push(seat);
        }


        total = seats.length * Number(price || 0);


        const totalEl = document.getElementById('total-price');
        const seatsEl = document.getElementById('selected-seats');

        if (totalEl) {
            totalEl.innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(total);
        }

        if (seatsEl) {
            seatsEl.innerText = seats.length ? seats.join(', ') : '-';
        }


        let btnCreateOrder = document.querySelector('#btnCreateOrder');
        if (btnCreateOrder) {
            if (seats.length > 0) {
                btnCreateOrder.style.background = "#112646";
                btnCreateOrder.style.color = "white";
                btnCreateOrder.classList.remove("bg-light");
                btnCreateOrder.onclick = CreateOrder;
            } else {
                btnCreateOrder.style.background = '';
                btnCreateOrder.style.color = '';
                btnCreateOrder.onclick = null;
            }
        }
    }

    function CreateOrder() {
        let data = {
            user_id: $("#user_id").val(),
            schedule_id: $("#schedule_id").val(),
            row_of_seat: seats,
            quantity: seats.length,
            total_price: totalPrice,
            tax: 4000 * seats.length,
            hour: $("#hour").val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: "{{ route('tickets.store') }}",
            method: "POST",
            data: data,
            success: function(response) {
               let ticketId = response.data.id;
               window.location.href = `/tickets/${ticketId}/order`;

            },
            error: function(message) {
                alert('Gagal membuat order, silakan coba lagi.');
            }
        });
    }
</script>
@endpush

