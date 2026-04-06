<style>
    .video-date-pill {
        display: block;
        padding: 10px;
        border-radius: 10px;
        background: #f5f5f5;
        text-align: center;
        text-decoration: none;
        color: #000;
        min-width: 80px;
   }

    .video-date-pill.selected-date {
        background: #7c3996;
        color: #fff;
    }

    .date-scroll-area {
        display: flex;
        overflow-x: auto;
        list-style: none;
        padding: 0;
    }

    .date-scroll-area li {
        margin-right: 10px;
    }
</style>
@if(count($ApiVCData) > 0)
@php $k = 0; @endphp

<div class="date-slider-wrapper">

    <img src="{{ asset('images/arrleft.png') }}" onclick="slideDate(-1)" style="cursor:pointer;">

    <div class="date-scroll-area" id="dateScroll">

        @foreach ($ApiVCData as $value)
            @php
                $yrdata = strtotime($value['SessionDate']);

                $dateNum = date('d', $yrdata);   // Day number
                $dayName = date('l', $yrdata);   // Full day name
                $monthName = date('F', $yrdata); // Month name

                $sessionDate = date('d, M Y', $yrdata);
            @endphp
            <div class="date-pill {{ $loop->first ? 'selected-date' : '' }}" onclick="pickDate1(this)"
                data-video-appointment-date="{{ date('Y-m-d', $yrdata) }}"
                data-video-session-date="{{ $sessionDate }}"
                data-video-morning-uid="{{ $value['SessionDefinitionUID1'] }}"
                data-video-evening-uid="{{ $value['SessionDefinitionUID2'] }}"
                data-video-organisation-uid="{{ $selectedBranch->UID }}"
            >
              <span class="date-num">{{ $dateNum }}</span>
              <span class="date-day">{{ $dayName }}</span>
              <span class="date-month">{{ $monthName }}</span>
            </div>

            @php $k++; @endphp
        @endforeach

    </div>

    <img src="{{ asset('images/arrright.png') }}" onclick="slideDate(+1)" style="cursor:pointer;">

</div>
<hr class="custom-hr">
<div class="selectTime">
    <ul class="timeList" id="slots-video-container"></ul>
    <div class="time-slots-grid" id="videoSlotsGrid">
        @php $first = true; @endphp

        @foreach($response1 as $t)
            @php
                $start = \Carbon\Carbon::parse($t['SessionStartDttm']);
                $end = \Carbon\Carbon::parse($t['SessionEndDttm']);

                $isFuture = now()->lt($start);
                $isFree = $t['BookingStatus'] === 'FREE';

                $timeRange = $start->format('g:ia') . ' - ' . $end->format('g:ia');

                $timeClass = $start->format('A') === 'AM' ? 'morning' : 'evening';
            @endphp

            @if($isFree && $isFuture)
                <div 
                    class="time-slot-pill {{ $first ? 'selected-slot' : '' }} {{ $timeClass }}"
                    onclick="pickSlot(this)"
                    data-orgcode="{{ $t['OrgCode'] }}"
                    data-slot-id="{{ $t['SlotID'] }}"
                    data-slot-date="{{ $start->format('d-m-Y') }}"
                    data-sessionuid="{{ $t['SessionDefinitionUID'] }}"
                    data-serviceuid="{{ $t['CareproviderCode'] }}"
                    data-doctoruid="{{ $t['CareproviderCode'] }}"
                    data-locationuid="{{ $t['LocationUID'] }}"
                    data-start-time="{{ $start->format('H:i') }}"
                    data-end-time="{{ $end->format('H:i') }}"
                    data-success-time="{{ $timeRange }}"
                >
                    {{ $timeRange }}
                </div>

                @php $first = false; @endphp
            @endif
        @endforeach
    </div>
</div>

    <button class="btn-book-modal" data-slotType="video" id="btn-book-modal" style="display: {{(empty($response1) && count($response1) == 0)?'none':''}}">Book Appointment</button>
    {{-- <button class="btn-book-modal" data-slotType="video" id="btn-book-modal" onclick="openConfirmModal()" style="display: {{(empty($response1) && count($response1) == 0)?'none':''}}">Book Appointment</button> --}}

    <div class="no-slots-box" id="noVideoSlotsBox" style="display: {{(isset($response1) && count($response1) > 0)?'none':''}}">
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

    function pickDate1(el) {
        $(".date-pill").removeClass("selected-date");
        $(el).addClass("selected-date");

        let apptDate = $(el).data("video-appointment-date");
        let morningUID = $(el).data("video-morning-uid");
        let orgUID = $(el).data("video-organisation-uid");

        $("#noVideoSlotsBox").hide();
        $("#btn-video-book-modal").hide();

        fetchVideoSlots(morningUID, apptDate, orgUID);
    }

    function fetchVideoSlots(sessionId, apptDate, orgUID) {
        if (!sessionId) return;

        let container = $("#videoSlotsGrid");

        container.empty();
        $('#page-loader').css('display', 'flex');
        $("#noVideoSlotsBox").hide();

        $.ajax({
            url: "/get-slots-by-session",
            type: "GET",
            data: {
                sessionId: sessionId,
                apptDate: apptDate,
                orgUID: orgUID,
                type: 'Video'
            },
            success: function (res) {
                container.empty();
                $('#page-loader').hide();

                let slotCount = 0;

                if (res && res.length > 0) {
                    res.forEach(function (t) {
                        let start = moment(t.SessionStartDttm);
                        let end = moment(t.SessionEndDttm);

                        if (t.BookingStatus === "FREE" && moment().isBefore(start)) {

                            let label = start.format('h:mma') + " - " + end.format('h:mma');

                            let html = `
                                <div class="time-slot-pill ${slotCount === 0 ? 'selected-slot' : ''} ${start.format('A') === 'AM' ? 'morning' : 'evening'}"
                                    onclick="pickSlot(this)"

                                    data-orgcode="${t.OrgCode}"
                                    data-slot-id="${t.SlotID}"
                                    data-slot-date="${start.format('DD-MM-YYYY')}"
                                    data-sessionuid="${t.SessionDefinitionUID}"
                                    data-serviceuid="${t.CareproviderCode}"
                                    data-doctoruid="${t.CareproviderCode}"
                                    data-locationuid="${t.LocationUID}"
                                    data-start-time="${start.format('HH:mm')}"
                                    data-end-time="${end.format('HH:mm')}"
                                    data-success-time="${label}"
                                >
                                    ${label}
                                </div>
                            `;

                            container.append(html);
                            slotCount++;
                        }
                    });
                }

                if (slotCount === 0) {
                    $("#noVideoSlotsBox").show();
                    $("#btn-video-book-modal").hide();
                } else {
                    $("#noVideoSlotsBox").hide();
                    $("#btn-video-book-modal").show();
                    $("#videoSlotsGrid .time-slot-pill:first").addClass("selected-slot");
                }
            },
            error: function () {
                container.html('<p>Error loading slots</p>');
                $("#noVideoSlotsBox").show();
            },
            complete: function () {
                $('#page-loader').hide();
            }
        });
    }

    $(document).on("click", "#btn-book-modal", function () {
        let selectedDate = $(".date-pill.selected-date").data("video-session-date");
        {{-- let selectedTime = $(".time-slot-pill.selected-slot").data("start-time"); --}}
        let selectedTime = $(".time-slot-pill.selected-slot").data("success-time");
        let startTime = selectedTime.split(" - ")[0];
        let slotId = $(".time-slot-pill.selected-slot").data("slot-id");
        let docName = $("#apptModalLabel").text();
        let docNameLabel = 'Online Consultation With '+ docName;

        $("#confirmConsultType").text(docNameLabel);
        $("#confirmDate").text(selectedDate);
        $("#confirmTime").text(startTime);
        $("#slottId").text(slotId);
        openConfirmModal();
    });


</script>