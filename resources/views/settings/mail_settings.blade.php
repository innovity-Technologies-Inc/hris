@extends('structure.master')

@section('content')
    <div class="py-3">
        <div class="row">
            <!-- Form Section -->
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-envelope-fill text-primary fs-5"></i>
                                </div>
                                <h4 class="mb-0 fw-bold">SMTP Configuration</h4>
                            </div>
                            @if (isset($mailSetting) && $mailSetting)
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#testMailModal">
                                    <i class="bi bi-send me-1"></i>Test Mail
                                </button>
                            @endif
                        </div>

                        <form id="mailSettingsForm" action="{{ route('settings.mail_settings.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ isset($mailSetting) ? $mailSetting->id : '' }}">

                            <div class="row g-3">
                                <!-- App Name -->
                                <div class="col-md-6">
                                    <label for="appName" class="form-label fw-semibold">
                                        Application Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="appName" name="app_name"
                                        placeholder="e.g., My Company"
                                        value="{{ isset($mailSetting) ? $mailSetting->app_name : old('app_name') }}"
                                        required>
                                    @error('app_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sender Email -->
                                <div class="col-md-6">
                                    <label for="senderEmail" class="form-label fw-semibold">
                                        Sender Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="senderEmail" name="sender_email"
                                        placeholder="noreply@example.com"
                                        value="{{ isset($mailSetting) ? $mailSetting->sender_email : old('sender_email') }}"
                                        required>
                                    @error('sender_email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mail Host -->
                                <div class="col-md-8">
                                    <label for="mailHost" class="form-label fw-semibold">
                                        SMTP Host <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="mailHost" name="mail_host"
                                        placeholder="smtp.gmail.com"
                                        value="{{ isset($mailSetting) ? $mailSetting->mail_host : old('mail_host') }}"
                                        required>
                                    @error('mail_host')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Port -->
                                <div class="col-md-4">
                                    <label for="port" class="form-label fw-semibold">
                                        Port <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="port" name="port"
                                        placeholder="587"
                                        value="{{ isset($mailSetting) ? $mailSetting->port : old('port') }}" min="1"
                                        max="65535" required>
                                    @error('port')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Encryption Type -->
                                <div class="col-md-12">
                                    <label for="encryptionType" class="form-label fw-semibold">
                                        Encryption <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="encryptionType" name="encryption_type" required>
                                        <option value="">Select Encryption</option>
                                        <option value="tls"
                                            {{ isset($mailSetting) && $mailSetting->encryption_type == 'tls' ? 'selected' : '' }}>
                                            TLS (Recommended)</option>
                                        <option value="ssl"
                                            {{ isset($mailSetting) && $mailSetting->encryption_type == 'ssl' ? 'selected' : '' }}>
                                            SSL</option>
                                        <option value="enc-type"
                                            {{ isset($mailSetting) && $mailSetting->encryption_type == 'enc-type' ? 'selected' : '' }}>
                                            None</option>
                                    </select>
                                    @error('encryption_type')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-md-12">
                                    <label for="password" class="form-label fw-semibold">
                                        Password / App Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Enter SMTP password"
                                            value="{{ isset($mailSetting) ? $mailSetting->password : old('password') }}"
                                            required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="card shadow border-0 mt-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-lightbulb text-warning me-2"></i>Quick Tips
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li class="mb-2">Always use TLS (Port 587) for better security</li>
                            <li class="mb-2">For Gmail/Yahoo, generate and use App Passwords instead of your regular
                                password</li>
                            <li class="mb-2">Test your settings by sending a test email after saving</li>
                            <li>Keep your SMTP credentials secure and never share them</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Instructions Section -->
            <div class="col-lg-4">
                <div class="card shadow border-0">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>How to Get SMTP Settings
                        </h6>

                        <!-- Gmail -->
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-google text-danger me-1"></i>Gmail
                            </h6>
                            <ul class="small mb-0 ps-3">
                                <li><strong>Host:</strong> smtp.gmail.com</li>
                                <li><strong>Port:</strong> 587 (TLS) or 465 (SSL)</li>
                                <li><strong>Encryption:</strong> TLS</li>
                                <li><strong>Password:</strong> Use <a href="https://myaccount.google.com/apppasswords"
                                        target="_blank">App Password</a></li>
                            </ul>
                            <div class="alert alert-warning small mt-2 mb-0 py-1 px-2">
                                <small>Enable 2-Step Verification first to generate App Password</small>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Outlook/Office365 -->
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-microsoft text-primary me-1"></i>Outlook / Office 365
                            </h6>
                            <ul class="small mb-0 ps-3">
                                <li><strong>Host:</strong> smtp.office365.com</li>
                                <li><strong>Port:</strong> 587</li>
                                <li><strong>Encryption:</strong> TLS</li>
                                <li><strong>Password:</strong> Your email password</li>
                            </ul>
                        </div>

                        <hr class="my-3">

                        <!-- Yahoo -->
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-yahoo text-purple me-1"></i>Yahoo Mail
                            </h6>
                            <ul class="small mb-0 ps-3">
                                <li><strong>Host:</strong> smtp.mail.yahoo.com</li>
                                <li><strong>Port:</strong> 587 or 465</li>
                                <li><strong>Encryption:</strong> TLS or SSL</li>
                                <li><strong>Password:</strong> Use <a href="https://login.yahoo.com/account/security"
                                        target="_blank">App Password</a></li>
                            </ul>
                        </div>

                        <hr class="my-3">

                        <!-- SendGrid -->
                        <div class="mb-0">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-send text-info me-1"></i>SendGrid
                            </h6>
                            <ul class="small mb-0 ps-3">
                                <li><strong>Host:</strong> smtp.sendgrid.net</li>
                                <li><strong>Port:</strong> 587 or 465</li>
                                <li><strong>Username:</strong> apikey</li>
                                <li><strong>Password:</strong> Your API Key</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Mail Modal -->
    <div class="modal fade" id="testMailModal" tabindex="-1" aria-labelledby="testMailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testMailModalLabel">
                        <i class="bi bi-envelope-check text-primary me-2"></i>Send Test Email
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="testMailForm" action="{{ route('settings.mail_settings.test') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>This will send a test email using your current mail settings to verify the
                                configuration.</small>
                        </div>
                        <div class="mb-3">
                            <label for="testRecipientEmail" class="form-label fw-semibold">
                                Recipient Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="testRecipientEmail" name="recipient_email"
                                placeholder="Enter recipient email" required>
                            <div class="form-text">Enter the email address where you want to receive the test email</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="sendTestMailBtn">
                            <i class="bi bi-send me-1"></i>Send Test Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });

        // Form validation
        document.getElementById('mailSettingsForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (field.value.trim() === '') {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });

        // Test Mail Form Submission
        document.getElementById('testMailForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('sendTestMailBtn');
            const originalBtnText = submitBtn.innerHTML;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';

            // Create FormData
            const formData = new FormData(form);

            // Send AJAX request
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('✅ Test email sent successfully! Please check your inbox.');
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('testMailModal')).hide();
                        // Reset form
                        form.reset();
                    } else {
                        // Show error message
                        alert('❌ Failed to send test email: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('❌ Error: ' + error.message);
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });
    </script>
@endsection
