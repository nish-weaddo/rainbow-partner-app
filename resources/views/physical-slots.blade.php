@if(count($All_Doctors) > 0)
@php $kk = 0; @endphp

<div class="date-slider-wrapper">
    <img src="{{ asset('images/arrleft.png') }}" onclick="slideDate(-1)" style="cursor:pointer;">
    <div class="date-scroll-area" id="dateScroll">

        @foreach ($All_Doctors as $value)
            @php
                $yrdata = strtotime($value['SessionDate']);

                $dateNum = date('d', $yrdata);   // 17
                $dayName = date('l', $yrdata);   // Monday
                $monthName = date('F', $yrdata); // December

                $sessionDate = date('d, M Y', $yrdata);
            @endphp

            <div class="date-pill {{ $loop->first ? 'selected-date' : '' }}" onclick="pickDate(this)"
                data-slot-time1="{{ $value['SlotTime1'] }}"
                data-slot-time2="{{ $value['SlotTime2'] }}"
                data-appointment-date="{{ date('Y-m-d', $yrdata) }}"
                data-Departmentcode="{{ $value['DepCode'] }}"
                data-morning-uid="{{ $value['SessionDefinitionUID1'] }}"
                data-evening-uid="{{ $value['SessionDefinitionUID2'] }}"
                data-session-date="{{ $sessionDate }}"
                data-organisation-uid="{{ $selectedBranch->UID }}" >
                <span class="date-num">{{ $dateNum }}</span>
                <span class="date-day">{{ $dayName }}</span>
                <span class="date-month">{{ $monthName }}</span>
            </div>
            @php $kk++; @endphp
        @endforeach
    </div>
    <img src="{{ asset('images/arrright.png') }}" onclick="slideDate(+1)" style="cursor:pointer;">
</div>
<hr class="custom-hr">
<div class="selectTime">
    <ul class="timeList" id="slots-container"></ul>
    <div class="time-slots-grid" id="timeSlotsGrid">
        @php $first = true; @endphp
        @if(isset($response2) && count($response2)>0)
            @foreach($response2 as $t)
                @php
                    $start = \Carbon\Carbon::parse($t['SessionStartDttm']);
                    $end = \Carbon\Carbon::parse($t['SessionEndDttm']);

                    $isFuture = now()->lt($start);
                    $isFree = $t['BookingStatus'] === 'FREE';

                    $label = $start->format('g:ia') . ' - ' . $end->format('g:ia');

                    $timeClass = $start->format('A') === 'AM' ? 'morning' : 'evening';
                @endphp

                @if($isFree && $isFuture)
                    <div 
                        class="time-slot-pill {{ $first ? 'selected-slot' : '' }} {{ $timeClass }}"
                        onclick="pickSlot(this)"

                        data-slot-id="{{ $t['SlotID'] }}"
                        data-date="{{ $start->format('n/j/Y') }}"
                        data-start-time="{{ $start->format('H:i') }}"
                        data-end-time="{{ $end->format('H:i') }}"
                        data-time="{{ $label }}"
                    >
                        {{ $label }}
                    </div>

                    @php $first = false; @endphp
                @endif
            @endforeach
        @endif
    </div>
</div>
    <button class="btn-book-modal" data-slotType="physical" id="btn-book-modal-1" style="display: {{(empty($response2) && count($response2) == 0)?'none':''}}">Book Appointment</button>

    <div class="no-slots-box" id="noSlotsBox" style="display: {{(isset($response2) && count($response2) > 0)?'none':''}}">
        <p class="no-slots-title">Slot Not Available</p>
        <p class="no-slots-subtitle">Book your slot on the next date</p>
    </div>
    
@else
<div class="text-center mt-4">
    <div class="no-slots-box" id="timeSlotsGrid">
        <p class="no-slots-title">Slot Not Available</p>
        <p class="no-slots-subtitle">Please fill the below form to request a callback</p>
        <div class="form">
            <input type="text" placeholder="Enter your Name" class="modal-input">
            <input type="text" placeholder="Enter your Number" class="modal-input">
            <a href="#" class="app-btn">Request a call back</a>
        </div>
    </div>

    {{-- <p>No slots are available. Please fill the below form to request a callback.</p> --}}
</div>
@endif

<script>
    function pickDate(el) {
        $(".date-pill").removeClass("selected-date");
        $(el).addClass("selected-date");

        let apptDate = $(el).data("appointment-date");
        let morningUID = $(el).data("morning-uid");
        let eveningUID = $(el).data("evening-uid");
        let orgUID = $(el).data("organisation-uid");

        {{-- $("#timeSlotsGrid").html('<p>Loading...</p>'); --}}
        $("#noSlotsBox").hide();
        $("#btn-book-modal").hide();

        fetchSlots(morningUID, apptDate, orgUID);
        {{-- fetchSlots(eveningUID, apptDate, orgUID); --}}
    }

    function fetchSlots(sessionId, apptDate, orgUID) {
        if (!sessionId) return;

        let container = $("#timeSlotsGrid");
        {{-- container.html('<p>Loading...</p>'); --}} // old loader
        $('#page-loader').css('display', 'flex');
        $("#noSlotsBox").hide();

        $.ajax({
            url: "/get-slots-by-session",
            type: "GET",
            data: {
                sessionId: sessionId,
                apptDate: apptDate,
                orgUID: orgUID,
                type: 'Physical'
            },
            success: function (res) {
                container.empty();
                $('#page-loader').hide();

                let slotCount = 0;
                {{-- console.log(res); --}}
                if (res && res.length > 0) {
                    res.forEach(function (t) {
                        let start = moment(t.SessionStartDttm);
                        let end = moment(t.SessionEndDttm);

                        if (t.BookingStatus === "FREE" && moment().isBefore(start)) {
                            let label = start.format('h:mma') + " - " + end.format('h:mma');

                            let html = `
                                <div class="time-slot-pill" onclick="pickSlot(this)"
                                    data-slot-id="${t.SlotID}"
                                    data-start-time="${start.format('HH:mm')}"
                                    data-end-time="${end.format('HH:mm')}"
                                    data-time="${label}">
                                    ${label}
                                </div>
                            `;
                            container.append(html);
                            $("#btn-book-modal").show();
                            slotCount++;
                        }
                    });
                }

                if (slotCount === 0) {
                    $("#noSlotsBox").show();
                    {{-- $("#btn-book-modal").hide(); --}}
                } else {
                    $("#noSlotsBox").hide();
                    $("#btn-book-modal").show();
                    $("#timeSlotsGrid .time-slot-pill:first").addClass("selected-slot");
                }
            },
            error: function () {
                container.html('<p>Error loading slots</p>');
            },
            complete: function () {
                $('#page-loader').hide();
            }
        });
    }

    $(document).on("click", "#btn-book-modal-1", function () {
        let selectedDate = $(".date-pill.selected-date").data("session-date");
        {{-- let selectedTime = $(".time-slot-pill.selected-slot").data("start-time"); --}}
        let selectedTime = $(".time-slot-pill.selected-slot").data("time");
        let startTime = selectedTime.split(" - ")[0];
        let slotId = $(".time-slot-pill.selected-slot").data("slot-id");
        let docName = $("#apptModalLabel").text();
        let docNameLabel = 'Physical Consultation With '+ docName;

        $("#confirmConsultType").text(docNameLabel);
        $("#confirmDate").text(selectedDate);
        $("#confirmTime").text(startTime);
        $("#slottId").text(slotId);
        openConfirmModal();
    });

</script>