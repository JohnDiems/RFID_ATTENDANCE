<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/RFIDLOGO.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RFID Attendance System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --primary-dark: #6d28d9;
            --secondary: #f5f3ff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --background: #ffffff;
            --text: #1e1b4b;
            --text-light: #6b7280;
            --card-bg: #ffffff;
            --violet-50: #f5f3ff;
            --violet-100: #ede9fe;
            --violet-200: #ddd6fe;
            --violet-500: #8b5cf6;
            --violet-600: #7c3aed;
            --violet-700: #6d28d9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--violet-50) 0%, white 100%);
            min-height: 100vh;
            color: var(--text);
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 0% 0%, var(--violet-100) 0%, transparent 45%),
                radial-gradient(circle at 100% 0%, var(--violet-200) 0%, transparent 45%),
                radial-gradient(circle at 100% 100%, var(--violet-100) 0%, transparent 45%),
                radial-gradient(circle at 0% 100%, var(--violet-200) 0%, transparent 45%);
            pointer-events: none;
            opacity: 0.6;
            z-index: 0;
            filter: blur(60px);
        }
        .clock-section {
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 10;
        }
        .time {
            font-family: 'Roboto Mono', monospace;
            font-size: 6.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--violet-600) 0%, var(--violet-500) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            line-height: 1;
            letter-spacing: 4px;
            filter: drop-shadow(0 4px 12px rgba(124, 58, 237, 0.15));
            text-shadow: 0 1px 2px rgba(124, 58, 237, 0.1);
        }

        .date {
            font-size: 2rem;
            font-weight: 600;
            margin-top: 1rem;
            color: var(--violet-600);
            text-align: center;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(124, 58, 237, 0.1);
        }
        .date {
            font-size: 1.8rem;
            color: var(--violet-600);
            margin-top: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .form-control:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 0.25rem rgba(33, 150, 243, 0.25);
        }
        #studentPhoto {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        #message-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            pointer-events: none;
            width: 300px;
        }

        .message {
            background: white;
            color: var(--violet-700);
            padding: 1.25rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.1),
                      0 4px 6px -2px rgba(139, 92, 246, 0.05);
            margin-bottom: 1rem;
            transform: translateX(120%);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            pointer-events: auto;
            font-weight: 500;
            font-size: 1rem;
            border-left: 5px solid var(--violet-500);
            max-width: 24rem;
            width: 100%;
            backdrop-filter: blur(8px);
            z-index: 50;
        }

        .message.show {
            transform: translateX(0);
        }

        .message i {
            margin-right: 12px;
            font-size: 20px;
        }

        .message.success {
            background: #f0fdf4;
            border-left-color: var(--success);
            color: var(--success);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1),
                      0 4px 6px -2px rgba(16, 185, 129, 0.05);
        }

        .message.error {
            background: #fef2f2;
            border-left-color: var(--danger);
            color: var(--danger);
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.1),
                      0 4px 6px -2px rgba(239, 68, 68, 0.05);
        }

        .message.info {
            background: var(--violet-50);
            border-left-color: var(--violet-500);
            color: var(--violet-700);
            box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.1),
                      0 4px 6px -2px rgba(139, 92, 246, 0.05);
        }

        .message i {
            font-size: 1.5rem;
            margin-right: 1rem;
            opacity: 0.9;
        }

        .toast-container {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            pointer-events: none;
            gap: 0.75rem;
        }

        .toast.success {
            border-left: 4px solid #00c853;
        }

        .toast.error {
            border-left: 4px solid #ff1744;
        }

        .toast.info {
            border-left: 4px solid #2196f3;
        }
        .BackgroundColor {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.05),
                      0 2px 4px -1px rgba(139, 92, 246, 0.03),
                      0 0 0 1px rgba(139, 92, 246, 0.025);
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--violet-100);
            backdrop-filter: blur(12px);
        }

        .BackgroundColor::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--violet-600), var(--violet-500));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .BackgroundColor:hover {
            background: var(--violet-50);
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(139, 92, 246, 0.1),
                      0 10px 10px -5px rgba(139, 92, 246, 0.04);
            border-color: var(--violet-200);
        }

        .BackgroundColor:hover::before {
            opacity: 1;
        }

        .info-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .PhotoBox {
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.2);
        }

        .PhotoBox img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .PhotoBox:hover img {
            transform: scale(1.05);
        }
        
        .BackgroundColor:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        .info-label {
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        .info-value {
            color: #1976d2;
            font-weight: 600;
            margin: 0;
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <!-- Toast Container for Messages -->
    <div class="toast-container"></div>

    <section class="w-100">
        <div class="container">
            <!-- Clock Section -->
            <div class="clock-section">
                <h1 class="time mb-0" id="time"></h1>
                <p class="date" id="date"></p>
            </div>

            <!-- Main Content -->
            <div class="row g-4">
                <!-- Left Column - Photo and RFID Input -->
                <div class="col-md-4">
                    <div class="BackgroundColor PhotoBox mb-3">
                        <img id="studentPhoto" class="img-fluid" >
                    </div>
                    <div class="input-group-lg">
                        <input type="text" 
                               class="form-control text-center" 
                               placeholder="Please Scan RFID" 
                               id="StudentRFID" 
                               name="StudentRFID" 
                               autofocus>
                    </div>
                </div>

                <!-- Right Column - Student Information -->
                <div class="col-md-8">
                    <!-- Name and Section -->
                    <div class="BackgroundColor p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label class="small text-muted mb-1">Name</label>
                                <h4 id="studentFullName" class="mb-0">-</h4>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Section</label>
                                <h4 id="studentCourse" class="mb-0">-</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Time In/Out -->
                    <div class="BackgroundColor p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label class="small text-muted mb-1">Time In</label>
                                <h4 id="studentIn" class="mb-0">-</h4>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Time Out</label>
                                <h4 id="studentOut" class="mb-0">-</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Lunch In/Out -->
                    <div class="BackgroundColor p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label class="small text-muted mb-1">Lunch In</label>
                                <h4 id="lunchIn" class="mb-0">-</h4>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Lunch Out</label>
                                <h4 id="lunchOut" class="mb-0">-</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="BackgroundColor p-3 mb-3">
                        <label class="small text-muted mb-1">Remarks</label>
                        <h4 id="studentStatus" class="mb-0">-</h4>
                    </div>

    
                </div>
            </div>
        </div>
    </section>
</div>



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Your custom JavaScript files -->
<script src="{{ asset('js/ajax.js') }}"></script>
<script src="{{ asset('js/realTime.js') }}"></script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/realTime.js') }}"></script>
    <script>
        // Show toast message
        function showStatusMessage(message, type = 'info') {
            const container = document.querySelector('.toast-container');
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast ${type} show`;
            
            // Set icon based on type
            let icon = 'info-circle';
            switch(type) {
                case 'success':
                    icon = 'check-circle';
                    break;
                case 'error':
                    icon = 'exclamation-circle';
                    break;
            }
            
            // Set toast content
            toast.innerHTML = `
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-${icon} me-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Add to container
            container.appendChild(toast);
            
            // Remove after delay
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Handle RFID form submission
        // Focus RFID input field and clear on load
        window.addEventListener('load', function() {
            const rfidInput = document.getElementById('StudentRFID');
            rfidInput.value = '';
            rfidInput.focus();
        });

        // Initialize clock with smooth updates
        const initClock = () => {
            const timeElement = document.getElementById('time');
            const dateElement = document.getElementById('date');
            
            // Clear any existing interval
            if (window.clockInterval) {
                clearInterval(window.clockInterval);
            }

            function updateClock() {
                const now = new Date();
                
                // Format time
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                
                // Update time immediately
                const newTime = `${hours}:${minutes}:${seconds}`;
                if (timeElement.textContent !== newTime) {
                    timeElement.textContent = newTime;
                }
                
                // Update date only once per minute or if empty
                if (seconds === '00' || !dateElement.textContent) {
                    const options = { 
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                };
                dateElement.textContent = now.toLocaleDateString('en-US', options);
            }
            
            // Set up smooth interval with RAF
            let lastUpdate = performance.now();
            let animationFrameId;

            function tick(timestamp) {
                const elapsed = timestamp - lastUpdate;
                
                if (elapsed >= 1000) { // Update every second
                    lastUpdate = timestamp - (elapsed % 1000);
                    updateClock();
                }
                
                animationFrameId = requestAnimationFrame(tick);
            }

            // Start the animation
            animationFrameId = requestAnimationFrame(tick);

            // Clean up on page unload
            window.addEventListener('unload', () => {
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                }
            });
        });

        // Focus RFID input on any key press
        document.addEventListener('keypress', (e) => {
            if (!e.target.matches('input')) {
                document.getElementById('StudentRFID').focus();
            }
        });

        // Handle AJAX response
        function handleAttendanceResponse(response) {
            if (response.success) {
                showStatusMessage(response.success, 'success');
                // Update student info display
                if (response.student) {
                    document.getElementById('studentFullName').textContent = response.student.name || '-';
                    document.getElementById('studentCourse').textContent = response.student.section || '-';
                    document.getElementById('studentIn').textContent = response.student.timeIn || '-';
                    document.getElementById('studentOut').textContent = response.student.timeOut || '-';
                    document.getElementById('lunchIn').textContent = response.student.lunchIn || '-';
                    document.getElementById('lunchOut').textContent = response.student.lunchOut || '-';
                    document.getElementById('studentStatus').textContent = response.student.status || '-';
                }
            } else if (response.error) {
                showStatusMessage(response.error, 'error');
            } else if (response.Invalidmessage) {
                showStatusMessage(response.Invalidmessage, 'error');
            }
            
            // Clear RFID input
            const rfidInput = document.getElementById('StudentRFID');
            if (rfidInput) {
                rfidInput.value = '';
                rfidInput.focus();
            }
        }
    </script>
</body>
</html>
