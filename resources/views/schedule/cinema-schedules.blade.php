@extends('templates.app')

@section('content')
    <div class="container my-5 card">
        <div class="card-body">
            <i class="fa-solid fa-location-dot  me-3"></i>{{ $schedules[0]['cinema']['location'] }}
            <hr>
            @foreach ($schedules as $schedule)
                <div class="my-2">
                    <div class="d-flex">
                        <div style="width: 150px; height:200px">
                            <img src="{{ asset('storage/' . $schedule['movie']['poster']) }}" alt=""
                                class="w-100" />
                        </div>
                        <div class="ms-5 mt-4">
                            <h5>{{ $schedule['movie']['title'] }}</h5>
                            <table>
                                <tr>
                                    <td><b class="text-secondary">Genre</b></td>
                                    <td class="px-3">:</td>
                                    <td>{{ $schedule['movie']['genre'] }}</td>
                                </tr>
                                <tr>
                                    <td><b class="text-secondary">Durasi</b></td>
                                    <td class="px-3">:</td>
                                    <td>{{ $schedule['movie']['duration'] }}</td>
                                </tr>
                                <tr>
                                    <td><b class="text-secondary">Sutradara</b></td>
                                    <td class="px-3">:</td>
                                    <td>{{ $schedule['movie']['director'] }}</td>
                                </tr>
                                <tr>
                                    <td><b class="text-secondary">Rating Usia</b></td>
                                    <td class="px-3">:</td>
                                    <td><span class="badge badge-danger">{{ $schedule['movie']['age_rating'] }}</span></td>
                                </tr>
                            </table>
                        </div>
                </div>
               <div class="w-100 my-3">
             <div class="d-flex justify-content-end">
                            <div>
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
                </div>
                <hr>
            @endforeach
            <div class="d-flex">
                <div style="width: 150px; height:200px">
                    <img src="{{ asset('storage/' . $schedule['poster']) }}" alt="" class="w-100" />
                </div>
                    </table>
                </div>
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
