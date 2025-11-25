@extends('templates.app')

@section('content')
    <div class="container pt-5">
        <div class="w-75 d-block m-auto">
            <div class="d-flex">
                <div style="width: 150px; height:200px">
                    <img src="{{ asset('storage/' . $movie['poster']) }}" alt="" class="w-100" />
                </div>
                <div class="ms-5 mt-4">
                    <h5>{{ $movie['title'] }}</h5>
                    <table>
                        <tr>
                            <td><b class="text-secondary">Genre</b></td>
                            <td class="px-3">:</td>
                            <td>{{ $movie['genre'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Durasi</b></td>
                            <td class="px-3">:</td>
                            <td>{{ $movie['duration'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Sutradara</b></td>
                            <td class="px-3">:</td>
                            <td>{{ $movie['director'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Rating Usia</b></td>
                            <td class="px-3">:</td>
                            <td><span class="badge badge-danger">{{ $movie['age_rating'] }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="w-100 row mt-5">
                <div class="col-6 pe-5">
                    <div class="d-flex flex-column justify-content-end align-items-end">
                        <div class="d-flex align-items-center">
                            <h3 class="text-warning me-2">9.2</h3>
                            <i class="fas fa-star text-warning me-2"></i>
                            <i class="fas fa-star text-warning me-2"></i>
                            <i class="fas fa-star text-warning me-2"></i>
                        </div>
                        <small>4.414 Votes</small>
                    </div>
                </div>
                <div class="col-6 ps-5" style="border-left: 2px solid #c7c7c7">
                    <div class="d-flex align-items-center">
                        <div class="fas fa-heart text-danger me-2"></div>
                        <b>Masukan Watchlist</b>
                    </div>
                    <small>10.000</small>
                </div>
            </div>
            <div class="w-100 bg-light mt-3">
                <div class="dropdown">
                    <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                        Bioskop
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        @foreach ($movie['schedules'] as $schedule)
                            <li><a class="dropdown-item" href="#">{{ $schedule['cinema']['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @php
                    if (request()->get('sort_price') == 'ASC') {
                        $sortPrice = 'DESC';
                    } elseif (request()->get('sort_price') == 'DESC')  {
                        $sortPrice = 'ASC';
                    } else {
                        $sortPrice = 'ASC';
                    }

                     if (request()->get('sort_alphabet') == 'ASC') {
                        $sortAlphabet = 'DESC';
                    } elseif (request()->get('sort_alphabet') == 'DESC')  {
                        $sortAlphabet = 'ASC';
                    } else {
                        $sortAlphabet = 'ASC';
                    }
                @endphp
                <div class="dropdown">
                    <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                        Sortir
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        @foreach ($movie['schedules'] as $schedule)
                           <li><a class="dropdown-item" href="?sort_price={{ $sortPrice }}">Harga{{ $schedule['cinema']['price'] }}</a></li>
                            <li><a class="dropdown-item" href="?sort_alphabet={{ $sortAlphabet }}">Alphabet {{ $schedule['cinema']['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="mb-5">
                @foreach ($movie['schedules'] as $schedule)
                    <div class="w-100 my-3">

                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="fa-solid fa-building"></i> <b class="ms-2">{{ $schedule['cinema']['name'] }}</b>
                                <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>
                            </div>
                            <div class="mt-2">
                                <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                            </div>
                        </div>
                        <br>
                        <div class="d-flex gap-3 ps-3 my-2">
                            @foreach (json_decode($schedule['hours']) as $index => $hour)
                                <button class="btn btn-outline-secondary">{{ $hour }}</button>
                                <div class="btn btn-outline-secondary"
                                style ="cursor: pointer" onclick="selectedHour('{{ $schedule['id'] }}', '{{ $index }}', this )" >{{ $hour }}
                            </div>
                            @endforeach

                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
           <div class="w-100 p-2 bg-light text-center fixed-bottom" id="wrapBtn">
                <a href="javascript:void(0)" id="btnOrder"><i class="fa-solid fa-ticket"></i> BELI TIKET</a>
            </div>
        </div>
    </div>
@endsection
@push('script')
   <script>
    let selectedScheduleId = null;
    let selectedHourIndex = null;
    let lastClicked = null;

    function selectedHour(scheduleId, hourIndex, el) {
        selectedScheduleId = scheduleId;
        selectedHourIndex = hourIndex;

        if (lastClicked) {
            lastClicked.style.backgroundColor = "";
            lastClicked.style.color = "";
            lastClicked.style.borderColor = "";
        }

        el.style.backgroundColor = "#112646";
        el.style.color = "white";
        el.style.borderColor = "#112646";

        lastClicked = el;

        let wrapBtn = document.querySelector("#wrapBtn");
        wrapBtn.classList.remove("bg-light");
        wrapBtn.style.backgroundColor = '#112646';

        let url = "{{ route('schedule.show_seats', ['schedule_id' => ':scheduleId', 'hourId' => ':hourId']) }}".replace(':scheduleId', scheduleId).replace(':hourId', hourIndex);

        let btnOrder = document.querySelector("#btnOrder");
        btnOrder.href = url;
        btnOrder.style.color = "white";
    }
</script>
@endpush
