<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/RFIDLOGO.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RFID Attendance System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        mono: ['Roboto Mono', 'monospace']
                    }
                }
            }
        }
    </script>
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
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-violet-50 to-white">
    <!-- Toast Container for Messages -->
    <div class="fixed top-4 right-4 z-50" id="toast-container"></div>

    <section class="w-full">
        <div class="container mx-auto px-4">
            <!-- Clock Section -->
            <div class="text-center mb-9 py-5">
                <h1 class="text-8xl font-mono font-semibold text-violet-700 mb-0" id="time"></h1>
                <p class="text-2xl text-violet-600" id="date"></p>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Left Column - User Information -->
                <div class="md:col-span-4">
                    <div class="bg-white rounded-lg shadow-md mb-4 border border-violet-200 overflow-hidden">
                        <div class="bg-violet-600 text-white text-center py-3">
                            <h5 class="text-lg font-semibold">Student Information</h5>
                        </div>
                        <div class="p-4 text-center">
                            <div class="mb-4 mt-2">
                                <img id="studentPhoto" class="w-40 h-40 mx-auto rounded-full border-3 border-violet-200 object-cover" >
                            </div>
                            <div class="mb-6">
                                <input type="text" 
                                       class="w-full py-3 px-4 text-center text-lg border border-violet-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent" 
                                       placeholder="Please Scan RFID" 
                                       id="StudentRFID" 
                                       name="StudentRFID" 
                                       maxlength="11"
                                       autocomplete="off"
                                       autofocus>
                                @csrf
                            </div>
                            <div class="border-t border-violet-100 pt-3">
                                <div class="flex mb-2 py-2 border-b border-violet-50">
                                    <div class="w-2/5 text-left font-semibold text-violet-700">Name:</div>
                                    <div id="studentFullName" class="w-3/5 text-left font-bold">-</div>
                                </div>
                                <div class="flex mb-2 py-2 border-b border-violet-50">
                                    <div class="w-2/5 text-left font-semibold text-violet-700">Section:</div>
                                    <div id="studentCourse" class="w-3/5 text-left">-</div>
                                </div>
                                <div class="flex mb-2 py-2 border-b border-violet-50">
                                    <div class="w-2/5 text-left font-semibold text-violet-700">Time In:</div>
                                    <div id="studentIn" class="w-3/5 text-left">-</div>
                                </div>
                                <div class="flex mb-2 py-2 border-b border-violet-50">
                                    <div class="w-2/5 text-left font-semibold text-violet-700">Time Out:</div>
                                    <div id="studentOut" class="w-3/5 text-left">-</div>
                                </div>
                                <div class="flex mb-2 py-2 border-b border-violet-50">
                                    <div class="w-2/5 text-left font-semibold text-violet-700">Lunch In:</div>
                                    <div id="lunchIn" class="w-3/5 text-left">-</div>
                                </div>
                                <div class="flex mb-2 py-2 border-b border-violet-50">
                                    <div class="w-2/5 text-left font-semibold text-violet-700">Lunch Out:</div>
                                    <div id="lunchOut" class="w-3/5 text-left">-</div>
                                </div>
                                <div class="flex py-2">
                                    <div class="w-2/5 text-left font-semibold text-violet-700">Status:</div>
                                    <div id="studentStatus" class="w-3/5 text-left">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Attendance Records Table -->
                <div class="md:col-span-8">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-violet-600 text-white text-center py-3">
                            <h5 class="text-lg font-semibold">Attendance Records</h5>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-violet-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-violet-700 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-violet-700 uppercase tracking-wider">Section</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-violet-700 uppercase tracking-wider">Time In</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-violet-700 uppercase tracking-wider">Lunch In</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-violet-700 uppercase tracking-wider">Lunch Out</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-violet-700 uppercase tracking-wider">Time Out</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-violet-700 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceTable" class="bg-white divide-y divide-gray-200">
                                        <!-- Attendance records will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('js/realTime.js') }}"></script>
    <script>
        // Show toast message
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            
            // Create toast element
            const toast = document.createElement('div');
            
            // Set toast classes based on type
            let bgColor = 'bg-violet-100 border-violet-500 text-violet-700';
            let icon = 'info-circle';
            
            switch(type) {
                case 'success':
                    bgColor = 'bg-green-100 border-green-500 text-green-700';
                    icon = 'check-circle';
                    break;
                case 'error':
                    bgColor = 'bg-red-100 border-red-500 text-red-700';
                    icon = 'exclamation-circle';
                    break;
            }
            
            toast.className = `${bgColor} border-l-4 p-4 mb-3 rounded shadow-md transform transition-all duration-300 ease-in-out`;
            
            // Set toast content
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${icon} mr-3"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Add to container
            container.appendChild(toast);
            
            // Add animation classes
            setTimeout(() => {
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);
            
            // Remove after 3 seconds with animation
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            // Get RFID input
            const rfidInput = document.getElementById('StudentRFID');
            let lastValue = '';
            let submitTimeout;
            let invalidAttempts = 0;
            const maxInvalidAttempts = 3;

            // Clear student info function
            function clearStudentInfo() {
                document.getElementById('studentFullName').textContent = '-';
                document.getElementById('studentCourse').textContent = '-';
                document.getElementById('studentIn').textContent = '-';
                document.getElementById('studentOut').textContent = '-';
                document.getElementById('studentStatus').textContent = '-';
                document.getElementById('studentPhoto').src = '';
            }

            // Reset page function
            function resetPage() {
                clearStudentInfo();
                showToast('Too many invalid attempts. Resetting...', 'error');
                invalidAttempts = 0;
            }

            // Update student info function
            function updateStudentInfo(data) {
                if (data.data) {
                    const profile = data.data.profile;
                    if (profile) {
                        document.getElementById('studentFullName').textContent = profile.FullName || '-';
                        document.getElementById('studentCourse').textContent = profile.Course || '-';
                        document.getElementById('studentIn').textContent = data.data.time_in || '-';
                        document.getElementById('studentOut').textContent = data.data.time_out || '-';
                        document.getElementById('studentStatus').textContent = data.data.status || '-';
                        if (profile.Photo) {
                            document.getElementById('studentPhoto').src = profile.Photo;
                        }
                    }
                }
            }

            // Submit RFID function
            async function submitRFID(rfid) {
                console.log('Submitting RFID:', rfid); // Debug log

                try {
                    const response = await fetch('/mark-attendance', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ StudentRFID: rfid })
                    });

                    const data = await response.json();
                    console.log('Response data:', data); // Debug log

                    if (data.status === 'success') {
                        showToast(data.message, 'success');
                        updateStudentInfo(data);
                        invalidAttempts = 0; // Reset on success
                    } else {
                        showToast(data.message || 'RFID not found', 'error');
                        clearStudentInfo();
                        invalidAttempts++;
                        console.log('Invalid attempts:', invalidAttempts); // Log invalid attempts
                        if (invalidAttempts >= maxInvalidAttempts) {
                            resetPage();
                        }
                    }
                } catch (error) {
                    console.error('Error during submission:', error); // Debug log
                    showToast('Failed to process RFID', 'error');
                    clearStudentInfo();
                } finally {
                    // Clear input and refocus
                    rfidInput.value = '';
                    rfidInput.focus();
                    lastValue = ''; // Reset lastValue to allow duplicate submissions
                }
            }

            // Handle RFID input
            rfidInput.addEventListener('input', function() {
                const currentValue = this.value.trim();
                
                // Clear any existing timeout
                if (submitTimeout) {
                    clearTimeout(submitTimeout);
                }

                // If length is between 8 and 11, submit after a very short delay
                if (currentValue.length >= 8 && currentValue.length <= 11) {
                    submitTimeout = setTimeout(() => {
                        if (currentValue !== lastValue) { // Prevent duplicate submissions
                            lastValue = currentValue;
                            submitRFID(currentValue);
                        }
                    }, 100); // 100ms delay
                }
            });

            // Keep focus on RFID input
            document.addEventListener('click', function() {
                rfidInput.focus();
            });

            // Focus on page load
            rfidInput.focus();
        });
    </script>
</body>
</html>
