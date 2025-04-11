$(document).ready(function () {
  // Initialize Bootstrap Datepicker
  $('#calendarFilter').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true,
    orientation: "auto"
  });

  // Initialize DataTable
  var table = $('#Studentfilter').DataTable({
    "createdRow": function (row, data, dataIndex) {
      var statusCell = $(row).find('td:eq(3)'); // Assuming 'Status' is the 4th column (index 3)
      var status = data[3]; // Assuming 'Status' is the 4th column (index 3)

      // Change the color based on the status
      if (status === 'late') {
        statusCell.css('color', 'red');
      } else if (status === 'present') {
        statusCell.css('color', 'green');
      }
    },
  });

  // Event listener for calendar filter
  $('#calendarFilter').on('change', function () {
    const selectedDate = $(this).val();
    table.column(2).search(selectedDate).draw();
  });

  // Event listener for status filter
  $('#statusFilter').on('change', function () {
    var selectedStatus = $(this).val();
    table.column(3).search(selectedStatus).draw(); // Assuming 'Status' is the 4th column (index 3)
  });

  // Optional: Remove the search label from DataTable wrapper
  $('.dataTables_filter label').contents().filter(function () {
    return this.nodeType === 3;
  }).remove();

  // Optional: Add placeholder to search input in the DataTable
  table.columns().every(function () {
    var column = this;
    $(`input[type="search"]`, column.footer()).attr('placeholder', 'Search');
  });
});

// // UPLOAD PHOTO ON STUDENTLIST ROUTE
// const image = document.getElementById("upload");
// const input = document.getElementById("photo");

// // FUNCTION CHANGE IMAGE INPUT FILE
// input.addEventListener("change", function () {
//   if (input.files && input.files[0]) {
//     image.src = URL.createObjectURL(input.files[0]);
//   }
// });
