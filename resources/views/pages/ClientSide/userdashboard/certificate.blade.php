<!DOCTYPE html>
<html lang="en" style="position: relative;min-height: 100%;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Certificate Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ URL::asset('css/ClientCSS/Contact-Form-Clean.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ClientCSS/Footer-Clean.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ClientCSS/Header-Blue.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ClientCSS/styles.css') }}">
    
    <style>
        /* Form section styling */
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .error-text {
            font-size: 0.85rem;
            display: block;
            margin-top: 5px;
        }
        
        input[type="radio"] {
            margin-right: 8px;
        }
        
        .form-check-label {
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .resident-alert {
            margin-bottom: 20px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-section {
                padding: 15px;
            }
        }
        
        /* Add styles for alert messages */
        .alert-float {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: none;
        }
        
        .fade-out {
            animation: fadeOut 3s;
        }
        
        @keyframes fadeOut {
            0% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>

<body style="margin: 0 0 100px;">
    <input type="hidden" id="current_resident" data-id="{{ session('resident.id') }}">

    @include('inc.client_nav')
    
    <!-- Add alert containers -->
    <div class="alert alert-success alert-float" id="success-alert" role="alert">
        <strong>Success!</strong> Your certificate request has been submitted successfully.
    </div>
    <div class="alert alert-danger alert-float" id="error-alert" role="alert">
        <strong>Error!</strong> <span id="error-message">There was a problem submitting your request.</span>
    </div>

    <section class="contact-clean" style="padding-bottom: 140px;">
        <form id="certificateRequestForm" action="/barangay/certificate" method="post">
            @csrf
            
            

            <!-- Session and Validation Handling -->
            @if(session('resident.id'))
                <input type="hidden" value="{{ session('resident.id') }}" id="resident_id" name="resident_id">
            @else
                <div class="alert alert-danger resident-alert">
                    You must be logged in to submit a request. <a href="/login" class="alert-link">Click here to login</a>
                </div>
            @endif
            
            @if($errors->has('resident_id'))
                <div class="alert alert-danger resident-alert">
                    {{ $errors->first('resident_id') }}
                    <a href="/login" class="alert-link">Please log in again</a>
                </div>
            @endif

            <h2 class="text-center">Certificate Request Form</h2>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h4 class="section-title">Personal Information</h4>
                
                <div class="form-group">
                    <label style="font-weight: bold;">Full Name</label>
                    <input class="form-control" type="text" name="name" placeholder="Enter Full Name" value="{{ old('name') }}">
                    @error('name')
                        <span class="text-danger error_text">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight: bold;">Age</label>
                            <input class="form-control" type="number" name="age" placeholder="Enter age" 
                                   value="{{ old('age') }}" onkeypress="return isNumberKey(event)">
                            @error('age')
                                <span class="text-danger error_text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight: bold;">Gender</label>
                            <div class="border solid pt-2 pl-2">
                                <input type="radio" id="male" name="gender" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }}>
                                <label for="male">Male</label><br>
                                <input type="radio" id="female" name="gender" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                                <label for="female">Female</label>
                                @error('gender')
                                    <span class="text-danger error_text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label style="font-weight: bold;">Complete Address</label>
                    <input class="form-control" type="text" name="address" placeholder="Enter Complete Address" value="{{ old('address') }}">
                    @error('address')
                        <span class="text-danger error_text">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight: bold;">Contact Number</label>
                            <input class="form-control" type="text" name="contact" placeholder="Enter Contact Number" value="{{ old('contact') }}">
                            @error('contact')
                                <span class="text-danger error_text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight: bold;">Email Address</label>
                            <input class="form-control" type="email" name="email" placeholder="Enter Email Address" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger error_text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Certificate Details Section -->
            <div class="form-section mt-4">
                <h4 class="section-title">Certificate Details</h4>
                
                <div class="form-group">
                    <label style="font-weight: bold;">Certificate Type</label>
                    <select name="request_type" class="form-control">
                        @if(count($certificate) > 0)
                            @foreach ($certificate as $cert)
                                <option value="{{ $cert->certificate_type }}" {{ old('request_type') == $cert->certificate_type ? 'selected' : '' }}>
                                    {{ $cert->certificate_type }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('request_type')
                        <span class="text-danger error_text">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label style="font-weight: bold;">Purpose of Request</label>
                    <textarea class="form-control" name="Description" placeholder="Please specify the purpose of this certificate request" 
                              rows="6" required minlength="10" maxlength="500">{{ old('Description') }}</textarea>
                    @error('Description')
                        <span class="text-danger error_text">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight: bold;">Requested Date</label>
                            <input class="form-control" type="date" name="request_date" required value="{{ old('request_date') }}">
                            @error('request_date')
                                <span class="text-danger error_text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight: bold;">Number of Copies Needed</label>
                            <select class="form-control" name="copies">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('copies') == $i ? 'selected' : '' }}>{{ $i }} Copy{{ $i > 1 ? 'ies' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information Section -->
            <div class="form-section mt-4">
                <h4 class="section-title">Additional Information</h4>
                
                <div class="form-group">
                    <label style="font-weight: bold;">Are you a registered voter in this barangay?</label>
                    <div class="border solid pt-2 pl-2">
                        <input type="radio" id="voter_yes" name="voter_status" value="Yes" {{ old('voter_status') == 'Yes' ? 'checked' : '' }}>
                        <label for="voter_yes">Yes</label><br>
                        <input type="radio" id="voter_no" name="voter_status" value="No" {{ old('voter_status') == 'No' ? 'checked' : '' }}>
                        <label for="voter_no">No</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms_agree" name="terms_agree" required {{ old('terms_agree') ? 'checked' : '' }}>
                    <label class="form-check-label" for="terms_agree">
                        I certify that the information provided is true and correct to the best of my knowledge.
                    </label>
                    @error('terms_agree')
                        <span class="text-danger error_text">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <button class="btn btn-primary" type="submit" id="submitBtn" @if(!session('resident.id')) disabled @endif>
                    <span class="button-text">Submit Request</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                <button class="btn btn-secondary" type="reset">Clear Form</button>
            </div>
        </form>
    </section>
    
    <footer class="footer-clean" style="background-color: gray;position: absolute;left: 0;bottom: 0;height: 174px;width: 100%;overflow: hidden;margin-top: 30px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-4 col-md-3 item">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#">Web design</a></li>
                        <li><a href="#">Development</a></li>
                        <li><a href="#">Hosting</a></li>
                    </ul>
                </div>
                <div class="col-sm-4 col-md-3 item">
                    <h3>About</h3>
                    <ul>
                        <li><a href="#">Company</a></li>
                        <li><a href="#">Team</a></li>
                        <li><a href="#">Legacy</a></li>
                    </ul>
                </div>
                <div class="col-sm-4 col-md-3 item">
                    <h3>Careers</h3>
                    <ul>
                        <li><a href="#">Job openings</a></li>
                        <li><a href="#">Employee success</a></li>
                        <li><a href="#">Benefits</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 item social">
                    <a href="#"><i class="icon ion-social-facebook"></i></a>
                    <a href="#"><i class="icon ion-social-twitter"></i></a>
                    <a href="#"><i class="icon ion-social-snapchat"></i></a>
                    <a href="#"><i class="icon ion-social-instagram"></i></a>
                    <p class="copyright">Company Name © {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            function showAlert(type, message) {
                const alertId = type === 'success' ? '#success-alert' : '#error-alert';
                if (type === 'error') {
                    $('#error-message').text(message);
                }
                $(alertId).fadeIn().addClass('fade-out');
                setTimeout(() => {
                    $(alertId).fadeOut().removeClass('fade-out');
                }, 3000);
            }

            $('#certificateRequestForm').on('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.find('.button-text').text('Submitting...');
                submitBtn.find('.spinner-border').removeClass('d-none');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showAlert('success');
                        // Reset form after successful submission
                        $('#certificateRequestForm')[0].reset();
                        // Optionally redirect after delay
                        setTimeout(() => {
                            window.location.href = '/barangay/certificate/status';
                        }, 2000);
                    },
                    error: function(xhr) {
                        let errorMessage = 'There was a problem submitting your request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('error', errorMessage);
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.button-text').text('Submit Request');
                        submitBtn.find('.spinner-border').addClass('d-none');
                    }
                });
            });

            // Existing validation code
            const form = document.querySelector('form');
            const residentIdInput = document.getElementById('resident_id');
            
            form.addEventListener('submit', function(e) {
                if (!residentIdInput?.value) {
                    e.preventDefault();
                    showAlert('error', 'Your session has expired. Please log in again.');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
                
                const termsAgree = document.getElementById('terms_agree');
                if (!termsAgree.checked) {
                    e.preventDefault();
                    showAlert('error', 'You must agree to the terms before submitting.');
                    termsAgree.focus();
                }
            });
            
            function isNumberKey(evt) {
                const charCode = (evt.which) ? evt.which : evt.keyCode;
                return !(charCode > 31 && (charCode < 48 || charCode > 57));
            }
            
            document.querySelector('input[name="age"]').addEventListener('keypress', isNumberKey);
        });
    </script>
</body>
</html>