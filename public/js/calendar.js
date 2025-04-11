$(document).ready( function () {
    $('#Table').DataTable();
} );

$(document).ready(function() {
  var selectedStartDate, selectedEndDate;

  // Initialize datepicker
  $('.input-group.date').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
  }).on('changeDate', function(e) {
    var id = $(this).find('input').attr('id');
    var selectedDate = e.format('yyyy-mm-dd');
    
    if (id === 'startDate') {
      selectedStartDate = selectedDate;
    } else if (id === 'endDate') {
      selectedEndDate = selectedDate;
    }
  });

  // Handle submit button click
  $('#submitBtn').click(function() {
    var message = '';
    
    if (selectedStartDate && selectedEndDate) {
      message = 'Selected Date Range: ' + selectedStartDate + ' to ' + selectedEndDate;
    } else {
      message = 'Please select both start and end dates.';
    }

    $('#selectedDates').text(message);
  });
});