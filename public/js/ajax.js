const messageQueue = [];
let isShowingMessage = false;

function showStatusMessage(message, type = 'info') {
    // Add message to queue
    messageQueue.push({ message, type });
    
    // If not currently showing a message, show the next one
    if (!isShowingMessage) {
        showNextMessage();
    }
}

function showNextMessage() {
    if (messageQueue.length === 0) {
        isShowingMessage = false;
        return;
    }

    isShowingMessage = true;
    const { message, type } = messageQueue.shift();

    // Get or create container
    let container = document.getElementById('message-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'message-container';
        document.body.appendChild(container);
    }

    // Create message element
    const messageEl = document.createElement('div');
    messageEl.className = `message ${type}`;

    // Add icon
    const icon = type === 'success' ? 'fa-circle-check' : 
                type === 'error' ? 'fa-circle-xmark' : 'fa-circle-info';

    messageEl.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
    `;
    // Add to container and show
    container.appendChild(messageEl);
    requestAnimationFrame(() => messageEl.classList.add('show'));

    // Remove after delay
    setTimeout(() => {
        messageEl.classList.remove('show');
        setTimeout(() => {
            messageEl.remove();
            // Show next message if any
            showNextMessage();
        }, 300);
    }, 3000);
}

$(document).ready(function() {
    let processingRFID = false;

    // Function to clear student info
    function clearStudentInfo() {
        ['studentFullName', 'studentCourse', 'studentIn', 'studentOut', 
         'lunchIn', 'lunchOut', 'studentStatus'].forEach(id => {
            $(`#${id}`).text('-');
        });
        $('#studentPhoto').attr('src', '');
    }

    // Function to update student info
    function updateStudentInfo(student) {
        if (!student) return;

        $('#studentFullName').text(student.name || '-');
        $('#studentCourse').text(student.section || '-');
        $('#studentIn').text(student.timeIn || '-');
        $('#studentOut').text(student.timeOut || '-');
        $('#lunchIn').text(student.lunchIn || '-');
        $('#lunchOut').text(student.lunchOut || '-');
        $('#studentStatus').text(student.status || '-');

        if (student.photo) {
            $('#studentPhoto').attr('src', student.photo);
        }
    }

    $('#StudentRFID').on('input', function() {
        const studentRFID = $(this).val();
        
        if (studentRFID.length >= 10 && !processingRFID) {
            processingRFID = true;
            $(this).prop('disabled', true);

            showStatusMessage('Scanning RFID card...', 'info');

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: 'POST',
                url: '/mark-attendance',
                data: {
                    StudentRFID: studentRFID,
                    _token: csrfToken
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);

                    // Clear existing info
                    clearStudentInfo();

                    if (response.Invalidmessage) {
                        showStatusMessage(response.Invalidmessage, 'error');
                    } else {
                        if (response.message) {
                            showStatusMessage(response.message, 'success');
                        }
                        updateStudentInfo(response.student);
                    }

                    // Clear info and show ready message after delay
                    setTimeout(() => {
                        clearStudentInfo();
                        showStatusMessage('Ready to scan RFID', 'info');
                    }, 4000);
                },
                error: function(xhr, status, error) {
                    const errorMessage = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                    showStatusMessage(errorMessage, 'error');
                    console.error('Error:', error);
                    clearStudentInfo();
                },
                complete: function() {
                    $('#StudentRFID').prop('disabled', false).val('').focus();
                    processingRFID = false;
                }
            });
        }
    });
});

// Auto-focus RFID input when clicking anywhere except on messages
function focusInput(event) {
    if (event.target.closest('#message-container')) return;
    
    const input = document.getElementById('StudentRFID');
    if (event.target.tagName !== 'INPUT' && input && !input.disabled) {
        input.focus();
        event.preventDefault();
    }
}

// Adding click event listener to the document body
document.body.addEventListener('click', focusInput);
