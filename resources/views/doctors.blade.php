<!doctype html>
<html lang="en">
	<head>
	  	<meta charset="utf-8" />
	  	<meta name="viewport" content="width=device-width, initial-scale=1" />
	  	<link rel="stylesheet" href="{{asset('css/style.css')}}" />
	  	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
	  	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
	  	<title>Rainbow</title>
	  	<style>
	  		#page-loader {
	  		  position: fixed;
	  		  top: 0;
	  		  left: 0;
	  		  width: 100%;
	  		  height: 100%;
	  		  background: rgba(255, 255, 255, 0.7);
	  		  z-index: 9999;
	  		  display: none;
	  		  justify-content: center;
	  		  align-items: center;
	  		}

	  		.spinner {
	  		  width: 50px;
	  		  height: 50px;
	  		  border: 5px solid #ddd;
	  		  border-top: 5px solid #007bff;
	  		  border-radius: 50%;
	  		  animation: spin 1s linear infinite;
	  		}

	  		@keyframes spin {
	  		  100% {
	  		    transform: rotate(360deg);
	  		  }
	  		}
	  	</style>
	</head>
	<body>
	  	<section class="header">
			<div class="container">
			  	<div class="row d-flex justify-content-between align-items-center">
					<div class="col-md-4"></div>
					<div class="col-md-4 d-flex justify-content-center align-items-center">
						<div class="logo">
						  	<img src="{{asset('images/rlogo.png')}}" alt="logo" />
						</div>
					</div>
			  		<div class="col-md-4 contact">
						<div class="phone d-flex align-items-center gap-2">
				  			<img src="{{asset('images/call.png')}}" alt="phone" />1800-2122-22
						</div>
					</div>
				</div>
	  		</div>
		</section>

		<section>
  			<div class="container">
				<h1 class="page-heading">{{ucwords($branch->name)}}, {{ucwords($region->name)}}</h1>
				<!-- Search Bar -->
				<div class="search-bar-row">
	  				<div class="dropdown" id="specialityDropdown">
						<button class="form-control d-flex justify-content-between align-items-center dropdown-toggle" type="button" data-bs-toggle="dropdown">
							<div class="d-flex align-items-center input-gap">
							  	<div class="input-text d-flex gap-2">
									<img src="{{asset('images/doctor.png')}}" alt="doc" />
									<span class="selected-text">Select Speciality</span>
							  	</div>
							  	<div>
									<img src="{{asset('images/arr.png')}}" alt="arrow" />
							  	</div>
							</div>
						</button>

					   	<ul class="dropdown-menu w-100">
					   		@if(isset($speciality_list) && count($speciality_list)>0)
		   			   		<li>
			   				      <input type="text" id="dropdownSearch" class="form-control" placeholder="Search...">
			   				   </li>
			   				   <li class="dropdown-item-item">
			   				      <a class="dropdown-item speciality-option" href="#" data-id="" data-name="Select Speciality">-- None --
			   				      </a>
			   				   </li>
					   			@foreach($speciality_list as $skeys=>$speciality)
					   				@if(!empty($speciality->name))
					   					<li class="dropdown-item-item">
					   						{{-- <a class="dropdown-item speciality-option" href="#" data-value="{{ $speciality->id }}">
												    {{ $speciality->name }}
												</a> --}}
												<a class="dropdown-item speciality-option {{ (request('speciality') == $speciality->id) ? 'active' : '' }}" href="#" data-id="{{ $speciality->id }}" data-name="{{ $speciality->name }}">
												   {{ $speciality->name }}
												</a>
			               			</li>
					   				@endif
					   			@endforeach
					   		@endif
						 </ul>
					</div>
					<div class="dropdown" id="doctorDropdown">
					  	<button class="form-control d-flex justify-content-between align-items-center dropdown-toggle" type="button" data-bs-toggle="dropdown">
								<div class="d-flex align-items-center input-gap">
									<div class="input-text d-flex gap-2">
									  	<img src="{{asset('images/search.png')}}" alt="search" />
									  	<span class="selected-text">Search for Doctors</span>
									</div>
									<div>
									  	<img src="{{asset('images/arr.png')}}" alt="arrow" />
									</div>
								</div>
							</button>

						<ul class="dropdown-menu w-100">
							@if($allDoctor && count($allDoctor)>0)
					   		<li>
							      <input type="text" id="dropdownDoctorSearch" class="form-control" placeholder="Search...">
							   </li>
							   <li class="doctor-search">
						         <a class="dropdown-item doctor-option" href="#" data-slug="" data-name="Search for Doctors">-- None --
						         </a>
						      </li>
								@foreach($allDoctor as $keys=>$doct)
									<li class="doctor-search">
										<a class="dropdown-item doctor-option  {{ (request('doctor') == $doct->slug) ? 'active' : '' }}" href="#" data-slug="{{ $doct->slug }}" 
										   data-name="{{ $doct->name }}">
										   {{ $doct->name }}
										</a>
										{{-- <a class="dropdown-item doctor-option" href="#" data-value="{{ $doct['slug'] }}">
										  {{ $doct['name'] }}
										</a> --}}
									</li>
								@endforeach
							@endif
						</ul>
					</div>
					<button class="btn-search-doctors">Search for Doctors</button>
				</div>

				<!-- Doctors Grid -->
				<div class="doctors-grid">
					@if(isset($doctors) && count($doctors)>0)
						@foreach($doctors as $key=>$doctor)
							<div class="doc-card">
								<div class="doc-card-inner">
			  						<img class="doc-photo" src="{{ config(key: 'costomVar.cms_url') . $doctor['small_image'] }}"
                                        alt="{{ $doctor['name'] }} - {{ $doctor['designation'] }}" />
								  	<div class="doc-info">
										<div class="doc-name">{{$doctor['name']}}</div>
										<div class="doc-designation">
										  {{$doctor['designation']}}
										</div>
										<ul class="doc-meta">
											@if($doctor['experience'])
										  		<li>
										  			<img src="{{asset('images/doc.png')}}" alt="doc" /> {{$doctor['experience']}}
										  		</li>
									  		@endif
									  		<li>
												<img src="{{asset('images/language.png')}}" alt="language" /> 
											{{$doctor['languages']}}
									  		</li>
											<li>
												<img src="{{asset('images/loc.png')}}" alt="location" /> {{$doctor['branchName']}}
									  		</li>
										</ul>
								  	</div>
								</div>
								<div class="doc-card-btns">
								  	<button class="btn-book btn-open-modal" data-region="{{$region->slug}}" data-branch="{{$branch->slug}}" data-doc="{{$doctor['slug']}}" data-id="{{$doctor['url']}}" data-designation="{{$doctor['designation']}}" data-doctname="{{$doctor['name']}}">Book Appointment</button>
								  	<button class="btn-call">Call Now</button>
								</div>
							</div>
						@endforeach
					@endif
				</div>
			</div>
		</section>

<!-- Appointment Modal -->
<!-- Appointment Modal -->

{{-- <div class="modal-body">
    <div class="slot-type-toggle">
        <button id="btn-physical" class="active">In Hospital</button>
        <button id="btn-video">Online Consult</button>
    </div>

    <div id="physical-container"></div>
    <div id="video-container" style="display:none;"></div>
</div> --}}


		<div class="modal fade appt-modal" id="appointmentModal" tabindex="-1" aria-labelledby="apptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:800px;">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <div>
          <div class="appt-doctor-name" id="apptModalLabel">Dr. Dinesh Kumar Chirla</div>
          <p class="appt-doctor-designation" id="doctor-designation">Director Intensive Care Services</p>
        </div>
        <button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body pt-0">

        <!-- Tab Toggle -->
        <div class="appt-tab-wrapper">
          <button class="appt-tab-btn active-tab" id="inHospitalTab" onclick="switchTab('inHospital')">In Hospital</button>
          <button class="appt-tab-btn inactive-tab" id="onlineTab" onclick="switchTab('online')">Online Consult</button>
        </div>

        <div class="color">
          <div id="slots-container"></div>
          {{-- <button class="btn-book-modal" id="btn-book-modal" onclick="openConfirmModal()">Book Appointment</button> --}}
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Confirm Appointment Modal -->
<div class="modal fade confirm-modal" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
	<div class="modal-content">
	  <div class="modal-header align-items-start">
		<h5 class="confirm-title">Confirm your appointment</h5>
		<button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body pt-2">
		<p class="confirm-consultation-type" id="confirmConsultType"></p>
		<div class="confirm-datetime-row">
		  <div class="confirm-datetime-item">
			<img src="{{asset('images/date.png')}}" alt="date">
			<span id="confirmDate"></span>
		  </div>
		  <div class="confirm-datetime-item">
			<img src="{{asset('images/clock.png')}}" alt="clock">
			<span id="confirmTime"></span>
			<input type="hidden" id="slottId" name="">
		  </div>
		</div>
		<p class="confirm-note">Please Enter The Registered Mobile Number For The Specific Patient</p>
		<p class="confirm-phone-label">Phone No.</p>
		<div class="confirm-phone-input-wrap">
		  <button class="confirm-country-code">+91 <i class="fa-solid fa-chevron-down"></i></button>
		  <div class="confirm-divider"></div>
		  <input class="confirm-phone-field" type="text" maxlength="10"
    oninput="validatePhone(this)" placeholder="Enter phone number"/>
		</div>
		<!-- <div class="phone-error-msg" id="phoneError">Please enter a valid mobile number.</div> -->
		<button class="btn-submit-modal" onclick="submitPhone()">Submit</button>
		
	  </div>
	</div>
  </div>
</div>

<!-- Patient Table Modal -->
{{-- <div class="modal fade patient-modal" id="patientModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
	<div class="modal-content">
	  <div class="modal-header align-items-start">
		<h5 class="confirm-title">Confirm your appointment</h5>
		<button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body pt-1">

		<p class="confirm-consultation-type" id="patientConsultType"></p>

		<div class="confirm-datetime-row mb-2">
		  <div class="confirm-datetime-item">
			<!-- <i class="fa-regular fa-calendar"></i> -->
			<img src="{{asset('images/date.png')}}" alt="date">
			<span id="patientDate"></span>
		  </div>
		  <div class="confirm-datetime-item">
			<img src="{{asset('images/clock.png')}}" alt="clock">
			<span id="patientTime"></span>
		  </div>
		</div>
		
		<p class="payable-amount">Payable Amount: 1200/-</p>
		<p class="confirm-note">Please Enter The Registered Mobile Number For The Specific Patient</p>
		
		<div class="patient-table-wrap">
		  <table class="patient-table">
			<thead>
			  <tr>
				<th>SNo.</th>
				<th>Patient Name</th>
				<th>UID</th>
				<th>Action</th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>1</td>
				<td>test</td>
				<td>MAB-00222569</td>
				<td>
				  <div class="patient-action-btns">
					<button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
					<button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
				  </div>
				</td>
			  </tr>
			  <tr>
				<td>3</td>
				<td>TESTING</td>
				<td>HBBTMP-070964</td>
				<td>
				  <div class="patient-action-btns">
					<button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
					<button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
				  </div>
				</td>
			  </tr>
			  <tr>
				<td>4</td>
				<td>TESTING</td>
				<td>HBBTMP-070964</td>
				<td>
				  <div class="patient-action-btns">
					<button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
					<button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
				  </div>
				</td>
			  </tr>
			  <tr>
				<td>5</td>
				<td>TESTING</td>
				<td>HBBTMP-070964</td>
				<td>
				  <div class="patient-action-btns">
					<button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
					<button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
				  </div>
				</td>
			  </tr>
			</tbody>
		  </table>
		</div>
		
		<button class="btn-final-submit">Submit</button>
		
	  </div>
	</div>
  </div>
</div> --}}
<div class="modal fade patient-modal" id="patientModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header align-items-start">
        <h5 class="confirm-title">Confirm your appointment</h5>
        <button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-1">

        <p class="confirm-consultation-type" id="patientConsultType"></p>

        <div class="confirm-datetime-row mb-2">
          <div class="confirm-datetime-item">
            <!-- <i class="fa-regular fa-calendar"></i> -->
             <img src="{{asset('images/date.png')}}" alt="date">
            <span id="patientDate"></span>
          </div>
          <div class="confirm-datetime-item">
            <img src="{{asset('images/clock.png')}}" alt="clock">
            <span id="patientTime"></span>
          </div>
        </div>

        <p class="payable-amount">Payable Amount: 1200/-</p>
        <p class="confirm-note">Please select the ID or add the new patient to proceed.</p>

        <div class="patient-table-wrap">
          <table class="patient-table">
            <thead>
              <tr>
                <th>SNo.</th>
                <th>Patient Name</th>
                <th>UID</th>
                <th class="hide-table">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><div class="number-desktop">1</div>
                  <div class="ckeckbox-mob"><input type="radio" name="option" class="custom-radio"></div>
                  </td>
                <td>test</td>
                <td>MAB-00222569</td>
                <td class="hide-table">
                  <div class="patient-action-btns">
                    <button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
                     <button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><div class="number-desktop">2</div>
                  <div class="ckeckbox-mob"><input type="radio" name="option" class="custom-radio"></div></td>
                <td>TESTING</td>
                <td>HBBTMP-070964</td>
                <td class="hide-table">
                  <div class="patient-action-btns">
                    <button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
                     <button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><div class="number-desktop">3</div>
                  <div class="ckeckbox-mob"><input type="radio" name="option" class="custom-radio"></div></td>
                <td>TESTING</td>
                <td>HBBTMP-070964</td>
                <td class="hide-table">
                  <div class="patient-action-btns">
                    <button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
                     <button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><div class="number-desktop">4</div>
                  <div class="ckeckbox-mob"><input type="radio" name="option" class="custom-radio"></div></td>
                <td>TESTING</td>
                <td>HBBTMP-070964</td>
                <td class="hide-table">
                  <div class="patient-action-btns">
                    <button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
                     <button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
         <div class="patient-action-btns-mobile">
                    <button class="btn-pay-now" onclick="openOtpModal()">Pay Now</button>
                     <button class="btn-pay-hospital" onclick="openBookedModal()">Pay at hospital</button>
                  </div>
        <!-- <button class="btn-final-submit">Submit</button> -->

      </div>
    </div>
  </div>
</div>

<!-- OTP Modal -->
<div class="modal fade otp-modal" id="otpModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
	<div class="modal-content">
	  <div class="modal-header align-items-start">
		<h5 class="otp-modal-title">Confirm your appointment</h5>
		<button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body pt-2">
	   
		<p class="otp-consult-type" id="otpConsultType"></p>
		
		<div class="otp-datetime-row">
		  <div class="otp-datetime-item">
			<!-- <i class="fa-regular fa-calendar"></i> -->
			<img src="{{asset('images/date.png')}}" alt="date">
			<span id="otpDate"></span>
		  </div>
		  <div class="otp-datetime-item">
			<img src="{{asset('images/clock.png')}}" alt="clock">
			<span id="otpTime"></span>
		  </div>
		</div>
		
		<p class="otp-payable-amount">Payable Amount: 1200/-</p>
		<p class="otp-sent-note" id="otpSentNote">We Have Sent You An OTP On <strong id="otpPhoneDisplay"></strong>.</p>

		<p class="otp-input-label">Enter OTP*</p>
		<input class="otp-input-field" id="otpInputField" type="text" maxlength="6" placeholder="Please Enter OTP"/>
		
		<p class="otp-resend-row">Still Not Received OTP? <a class="otp-resend-link" onclick="resendOtp()">Resent</a></p>
		
		<button class="btn-confirm-otp" onclick="openBookedModal()">Confirm OTP</button>
		
	  </div>
	</div>
  </div>
</div>
<!-- Booking Confirmed Modal -->
<div class="modal fade booked-modal" id="bookedModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	  </div>
	  <div class="modal-body p-12 pt-0">
		<div class="booked-checkmark-circle">
		  <img src="{{asset('images/check.png')}}" alt="check">
		  <polyline points="4,13 9,18 20,7" stroke="#22a84a" stroke-width="2.5" .../>
		</svg>
	  </div>
	  <h4 class="booked-heading">Booking Confirmed</h4>
	  <p class="booked-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
	  <div class="booked-doctor-card">
		<img class="booked-doctor-photo" src="{{asset('images/Dr. Dinesh Kumar Chirla.png')}}" alt="Dr. Dinesh Kumar Chirla"/>
		<div>
		  <p class="booked-doctor-name">Dr. Dinesh Kumar Chirla</p>
		  <p class="booked-doctor-designation">Director Intensive Care Services</p>
		</div>
	  </div>
	</div>
  </div>
</div>
</div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
   <script>
    /* ─── Tab Data (update independently) ─── */
    const tabData = {
      inHospital: {
        dates: [
          { num: '17', day: 'Monday',    month: 'December' },
          { num: '18', day: 'Tuesday',   month: 'December' },
          { num: '19', day: 'Wednesday', month: 'December' },
          { num: '20', day: 'Thursday',  month: 'December', selected: true },
          { num: '21', day: 'Friday',    month: 'December' },
          { num: '22', day: 'Saturday',  month: 'December' },
          { num: '23', day: 'Sunday',    month: 'December' },
          { num: '24', day: 'Monday',    month: 'December' },
        ],
        slots: [] // ← empty = "Slot Not Available" state
      },
      online: {
        dates: [
          { num: '20', day: 'Thursday',  month: 'December' },
          { num: '21', day: 'Friday',    month: 'December', selected: true },
          { num: '22', day: 'Saturday',  month: 'December' },
          { num: '23', day: 'Sunday',    month: 'December' },
          { num: '24', day: 'Monday',    month: 'December' },
          { num: '25', day: 'Tuesday',   month: 'December' },
          { num: '26', day: 'Wednesday', month: 'December' },
          { num: '27', day: 'Thursday',  month: 'December' },
          
        ],
        slots: [
          { time: '10:00am - 10:30am', selected: true },
          { time: '10:30am - 11:00am' },
          { time: '12:00pm - 12:30pm' },
          { time: '12:30pm - 1:00pm' },
          { time: '2:00pm - 2:30pm' },
          { time: '3:00pm - 3:30pm' },
          { time: '4:00pm - 4:30pm' },
          { time: '5:00pm - 5:30pm' },
        ]
      }
    };
 
    let activeTab = 'inHospital';
 
    /* ─── Render dates & slots for active tab ─── */
    function renderTab(tab) {
      const data = tabData[tab];
 
      // Dates
      const dateScroll = document.getElementById('dateScroll');
      dateScroll.innerHTML = data.dates.map(d => `
        <div class="date-pill ${d.selected ? 'selected-date' : ''}" onclick="pickDate(this)">
          <span class="date-num">${d.num}</span>
          <span class="date-day">${d.day}</span>
          <span class="date-month">${d.month}</span>
        </div>
      `).join('');
      dateScroll.scrollLeft = 0;
 
      // Slots
      const slotsGrid = document.getElementById('timeSlotsGrid');
      if (data.slots.length === 0) {
        const bookBtn = document.getElementById('btn-book-modal');
  if (bookBtn) bookBtn.style.display = 'none';
        slotsGrid.className = 'no-slots-box';
        slotsGrid.innerHTML = `
          <p class="no-slots-title">Slot Not Available</p>
          <p class="no-slots-subtitle">Book your slot on the next date</p>
           <div class="form">
          <input type="text" placeholder="Enter your Name" class="modal-input"/>
          <input type="text" placeholder="Enter your Number" class="modal-input"/>
          <a href="#" class="app-btn">Request a call back</a>
          </div>
        `;
      } else {
         const bookBtn = document.getElementById('btn-book-modal');
  if (bookBtn) bookBtn.style.display = 'block';
        slotsGrid.className = 'time-slots-grid';
        slotsGrid.innerHTML = data.slots.map(s => `
          <div class="time-slot-pill ${s.selected ? 'selected-slot' : ''}" onclick="pickSlot(this)">${s.time}</div>
        `).join('');
      }
    }
 
    /* ─── Tab switch ─── */
    {{-- function switchTab(tab) {
      activeTab = tab;
      document.getElementById('inHospitalTab').className = 'appt-tab-btn ' + (tab === 'inHospital' ? 'active-tab' : 'inactive-tab');
      document.getElementById('onlineTab').className     = 'appt-tab-btn ' + (tab === 'online'     ? 'active-tab' : 'inactive-tab');
      renderTab(tab);
    } --}}
 		
 		{{-- function switchTab(type) {
	    if (type === 'inHospital') {
	        $('#inHospitalTab').addClass('active-tab').removeClass('inactive-tab');
	        $('#onlineTab').addClass('inactive-tab').removeClass('active-tab');

	        let html = $('#appointmentModal').data('physicalHtml');
	        $('#dateScroll').html(html);

	    } else {
	        $('#onlineTab').addClass('active-tab').removeClass('inactive-tab');
	        $('#inHospitalTab').addClass('inactive-tab').removeClass('active-tab');

	        let html = $('#appointmentModal').data('videoHtml');
	        $('#dateScroll').html(html);
	    }

	    // reset slots grid when switching
	    $('#timeSlotsGrid').html('');
	} --}}

	function switchTab(type) {

	    let html = '';

	    if (type === 'inHospital') {
	        html = $('#appointmentModal').data('physicalHtml');

	        $('#inHospitalTab').addClass('active-tab').removeClass('inactive-tab');
	        $('#onlineTab').addClass('inactive-tab').removeClass('active-tab');

	    } else {
	        html = $('#appointmentModal').data('videoHtml');

	        $('#onlineTab').addClass('active-tab').removeClass('inactive-tab');
	        $('#inHospitalTab').addClass('inactive-tab').removeClass('active-tab');
	    }

	    $('#slots-container').html(html);
	}

    function pickDate(el) {
      document.querySelectorAll('.date-pill').forEach(p => p.classList.remove('selected-date'));
      el.classList.add('selected-date');
    }
 
    function pickSlot(el) {
      document.querySelectorAll('.time-slot-pill').forEach(p => p.classList.remove('selected-slot'));
      el.classList.add('selected-slot');
    }
 
    function slideDate(dir) {
      const area = document.getElementById('dateScroll');
      const pill = area.querySelector('.date-pill');
      area.scrollLeft += dir * (pill.offsetWidth + 6);
    }
 
    /* ─── Submit phone → 10-digit validation → patient table modal ─── */
    function submitPhone() {
  const phoneInput = document.querySelector('.confirm-phone-field');
  const phone = phoneInput.value.trim();

  if (!/^\d{10}$/.test(phone)) {
    phoneInput.value = "";
    phoneInput.placeholder = "Please enter a valid mobile number";
    phoneInput.style.borderColor = '#e53e3e';
    return;
  }

  // reset
  phoneInput.placeholder = "Value";
  phoneInput.style.borderColor = '#ddd';

  // Store phone
  window._confirmedPhone = phone;

  document.getElementById('patientConsultType').textContent = document.getElementById('confirmConsultType').textContent;
  document.getElementById('patientDate').textContent        = document.getElementById('confirmDate').textContent;
  document.getElementById('patientTime').textContent        = document.getElementById('confirmTime').textContent;

  bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
  new bootstrap.Modal(document.getElementById('patientModal')).show();
}
 
    /* ─── Pay Now → OTP Modal ─── */
    function openOtpModal() {
      document.getElementById('otpConsultType').textContent = document.getElementById('patientConsultType').textContent;
      document.getElementById('otpDate').textContent        = document.getElementById('patientDate').textContent;
      document.getElementById('otpTime').textContent        = document.getElementById('patientTime').textContent;
      document.getElementById('otpPhoneDisplay').textContent = window._confirmedPhone || '';
      document.getElementById('otpInputField').value = '';
 
      bootstrap.Modal.getInstance(document.getElementById('patientModal')).hide();
      new bootstrap.Modal(document.getElementById('otpModal')).show();
    }
 
    function resendOtp() {
      const note = document.getElementById('otpSentNote');
      note.innerHTML = 'OTP has been resent to <strong>' + (window._confirmedPhone || '') + '</strong>.';
    }
 
    /* ─── Open Confirm Modal with selected date & time ─── */
    function openConfirmModal() {
      // Get selected date
      const selectedDatePill = document.querySelector('.date-pill.selected-date');
      const selectedSlotPill = document.querySelector('.time-slot-pill.selected-slot');
 
      if (!selectedSlotPill) {
        alert('Please select a time slot.');
        return;
      }
 
      const num   = selectedDatePill.querySelector('.date-num').textContent;
      const month = selectedDatePill.querySelector('.date-month').textContent;
      const year  = '2024';
 
      // Format month abbreviation
      const monthMap = {
        January:'Jan', February:'Feb', March:'Mar', April:'Apr',
        May:'May', June:'Jun', July:'Jul', August:'Aug',
        September:'Sep', October:'Oct', November:'Nov', December:'Dec'
      };
      const monthAbbr = monthMap[month] || month;
 
      // Format time (e.g. "8:00am - 8:30am" → "8:00 AM")
      const rawTime = selectedSlotPill.textContent.trim().split('-')[0].trim();
      const formattedTime = rawTime.replace('am', ' AM').replace('pm', ' PM');
 
      // Consultation type label
      const consultType = activeTab === 'inHospital'
        ? 'Physical Consultation With Dr. Dinesh Kumar Chirla'
        : 'Online Consultation With Dr. Dinesh Kumar Chirla';
 
      // Populate confirm modal
      {{-- document.getElementById('confirmConsultType').textContent = consultType; --}}
      {{-- document.getElementById('confirmDate').textContent = `${num}, ${monthAbbr} ${year}`; --}}
      {{-- document.getElementById('confirmTime').textContent = formattedTime; --}}
 
      // Hide appointment modal, show confirm modal
      bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();
      new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }
 
    /* ─── Init on modal open ─── */
    document.getElementById('appointmentModal').addEventListener('show.bs.modal', () => {
      switchTab('inHospital');
    });
 
    /* ─── Clear phone error on input ─── */
    {{-- document.addEventListener('DOMContentLoaded', () => {
      document.querySelector('.confirm-phone-field').addEventListener('input', function () {
        document.getElementById('phoneError').style.display = 'none';
        this.style.borderColor = '#ddd';
      });
    }); --}}
   
       /* ─── Open Booking Confirmed Modal ─── */
    function openBookedModal() {
      const patientEl = document.getElementById('patientModal');
      const otpEl     = document.getElementById('otpModal');
      const patientInstance = bootstrap.Modal.getInstance(patientEl);
      const otpInstance     = bootstrap.Modal.getInstance(otpEl);
      if (patientInstance && patientEl.classList.contains('show')) {
        patientEl.addEventListener('hidden.bs.modal', function handler() {
          patientEl.removeEventListener('hidden.bs.modal', handler);
          new bootstrap.Modal(document.getElementById('bookedModal')).show();
        });
        patientInstance.hide();
      } else if (otpInstance && otpEl.classList.contains('show')) {
        otpEl.addEventListener('hidden.bs.modal', function handler() {
          otpEl.removeEventListener('hidden.bs.modal', handler);
          new bootstrap.Modal(document.getElementById('bookedModal')).show();
        });
        otpInstance.hide();
      } else {
        new bootstrap.Modal(document.getElementById('bookedModal')).show();
      }
    }
  </script>
  <script>
  function setupDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const items = dropdown.querySelectorAll(".dropdown-item");
    const text = dropdown.querySelector(".selected-text");

    items.forEach(item => {
      item.addEventListener("click", function (e) {
        e.preventDefault();
        text.innerText = this.innerText;
      });
    });
  }

  // init both independently
  setupDropdown("specialityDropdown");
  setupDropdown("doctorDropdown");
</script>
  <script>
{{-- document.querySelector('.confirm-phone-field').addEventListener('input', function () {
  this.placeholder = "Value";
  this.style.borderColor = '#ddd';
}); --}}
</script>

  <script>
  let selectedSpeciality = "";
  let selectedDoctor = "";

  // Speciality select
  document.querySelectorAll('.speciality-option').forEach(item => {
      item.addEventListener('click', function(e) {
          e.preventDefault();

          selectedSpeciality = this.dataset.id;
          let name = this.dataset.name;

          document.querySelector('#specialityDropdown .selected-text')
              .innerText = name;
      });
  });

  // Doctor select
  document.querySelectorAll('.doctor-option').forEach(item => {
      item.addEventListener('click', function(e) {
          e.preventDefault();

          selectedDoctor = this.dataset.slug; // backend value
          let name = this.dataset.name;       // UI value

          document.querySelector('#doctorDropdown .selected-text')
              .innerText = name;
      });
  });
  </script>

  <script>

  	document.getElementById('dropdownSearch').addEventListener('keyup', function() {
  	    let filter = this.value.toLowerCase();
  	    let items = document.querySelectorAll('.dropdown-item-item');

  	    items.forEach(function(item) {
  	        let text = item.querySelector('.speciality-option').textContent.toLowerCase();

  	        if (text.includes(filter)) {
  	            item.style.display = "";
  	        } else {
  	            item.style.display = "none";
  	        }
  	    });
  	});

  	document.getElementById('dropdownDoctorSearch').addEventListener('keyup', function() {
  	    let filter = this.value.toLowerCase();
  	    let items = document.querySelectorAll('.doctor-search');

  	    items.forEach(function(item) {
  	        let text = item.querySelector('.doctor-option').textContent.toLowerCase();

  	        if (text.includes(filter)) {
  	            item.style.display = "";
  	        } else {
  	            item.style.display = "none";
  	        }
  	    });
  	});

  document.querySelector('.btn-search-doctors').addEventListener('click', function() {

      let pathArray = window.location.pathname.split('/');

      let region = pathArray[2] || '';
      let branch = pathArray[3] || '';

      let url = "/doctors/" + region + "/" + branch + "?";

      if (selectedSpeciality) {
          url += "speciality=" + encodeURIComponent(selectedSpeciality) + "&";
      }

      if (selectedDoctor) {
          url += "doctor=" + encodeURIComponent(selectedDoctor);
      }

      url = url.replace(/&$/, '');

      window.location.href = url;
  });
  </script>
  <script>
  window.addEventListener('DOMContentLoaded', function() {

      // Get URL parameters
      const urlParams = new URLSearchParams(window.location.search);
      const specialityParam = urlParams.get('speciality');
      const doctorParam = urlParams.get('doctor');

      // Auto-select speciality
      if (specialityParam) {
          const specialityOption = document.querySelector(`.speciality-option[data-id="${specialityParam}"]`);
          if (specialityOption) {
              // Update dropdown text
              document.querySelector('#specialityDropdown .selected-text')
                  .innerText = specialityOption.dataset.name;

              // Set variable for filtering
              selectedSpeciality = specialityOption.dataset.id;

              // Add active class
              document.querySelectorAll('.speciality-option').forEach(el => el.classList.remove('active'));
              specialityOption.classList.add('active');
          }
      }

      // Auto-select doctor
      if (doctorParam) {
          const doctorOption = document.querySelector(`.doctor-option[data-slug="${doctorParam}"]`);
          if (doctorOption) {
              // Update dropdown text
              document.querySelector('#doctorDropdown .selected-text')
                  .innerText = doctorOption.dataset.name;

              // Set variable for filtering
              selectedDoctor = doctorOption.dataset.slug;

              // Add active class
              document.querySelectorAll('.doctor-option').forEach(el => el.classList.remove('active'));
              doctorOption.classList.add('active');
          }
      }

  });
  </script>

  <script>
  document.querySelectorAll('.btn-open-modal').forEach(button => {
    button.addEventListener('click', function() {
      let doctorUrl = this.dataset.id;
      let branch = this.dataset.branch;
      let region = this.dataset.region;
      let docName = this.dataset.doc;
      let designation = this.dataset.designation;
      let doctname = this.dataset.doctname;

      $('#page-loader').css('display', 'flex');
      $.ajax({
          url: '/get-session-by-doctor',
          type: 'GET',
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          data: {
              doctor: doctorUrl,
              branch: branch,
              region: region,
              name:docName
          },
          success: function (response) {
          	{{-- console.log(response); --}}
            $('#slots-container').html(response.physical);
            $('#appointmentModal').data('videoHtml', response.video);
            $('#appointmentModal').data('physicalHtml', response.physical);
            $("#apptModalLabel").text(doctname)
            $("#doctor-designation").text(designation)
            let modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
        modal.show();
          },
          error: function (xhr, status, error) {
            console.log(error);
          },
          complete: function () {
            $('#page-loader').hide();
          }
      });
    });
  });

  $("#btn-book-modal").on('click', function(){
  	alert("working on Book Appointment button");
  })

  </script>
  <div id="page-loader">
    <div class="spinner"></div>
  </div>
</body>
</html>
