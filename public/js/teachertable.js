$(document).ready(function () {

  // Initialize Bootstrap Datepicker
  $('#calendarFilter').datepicker({
    format: 'yyyy-mm-dd',  // Customize the date format as needed
    autoclose: true,
    todayHighlight: true,
    orientation: "auto"
  });
  
  const gradeSections = {
    'GRADE 7': ['ST. AGNES', 'ST. ANNE', 'ST. BERNADETTE'],
    'GRADE 8': ['OLGC', 'OLHR', 'OLMC'],
    'GRADE 9': ['ST. FRANCIS', 'ST. PETER', 'ST. THOMAS'],
    'GRADE 10': ['ST. JOHN', 'ST. LUKE', 'ST. MATTHEW']
  };

  let table1 = $('#filter2').DataTable({
    "oLanguage": {
      "sInfo": "",
    },
    dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
      "<'row'<'col-sm-12'B>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
      'excel', 'pdf', 'print',
      {
        text: 'Reset Filters',
        action: function (e, dt, node, config) {
          // Clear all filters
          $('#nameFilter, #statusFilter, #gradeFilter, #sectionFilter').val('');
          table1.columns().search('').draw();
        }
      }
    ],
    // Enable sorting on the first column (NAME)
    "aoColumnDefs": [
      { 'bSortable': true, 'aTargets': [0] }
    ],
    // Initial sorting by the first column in ascending order
    "order": [[0, 'asc']],
    // Customize the appearance of each row
    "createdRow": function (row, data, dataIndex) {
      var statusCell = $(row).find('td:eq(6)');
      var status = data[6];

      // Change the color based on the status
      if (status === 'late') {
        statusCell.css('color', 'red');
      } else if (status === 'present') {
        statusCell.css('color', 'green');
      }
    }
  });

  // Event listener for calendar filter
  $('#calendarFilter').on('change', function () {
    const selectedDate = $(this).val();
    table1.column(2).search(selectedDate).draw();
  });

  // Event listener for name filter
  $('#nameFilter').on('input', function () {
    table1.column(0).search(this.value).draw();
  });

  // Event listener for status filter
  $('#statusFilter').on('change', function () {
    table1.column(6).search(this.value).draw();
  });

  // Event listener for grade level dropdown
  $('#gradeFilter').on('change', function () {
    const selectedGrade = $(this).val();
    const sections = gradeSections[selectedGrade] || [];

    // Update the section dropdown
    updateSectionDropdown(sections);

    // Redraw the DataTable to reflect the changes
    table1.draw();
  });

  // SECTION TEACHER DATA TABLE
  // const SectionTeacher = $('#SectionTeacher').DataTable({});

  // REMOVE THE SEARCH LABEL FROM DATATABLE WRAPPER
  $('.dataTables_filter label').contents().filter(function () {
    return this.nodeType === 3;
  }).remove();

  // ADD PLACEHOLDER TO SEARCH INPUT
  table1.columns().every(function () {
    var column = this;
    $(`input[type="search"]`, column.footer()).attr('placeholder', 'Search');
  });

  // Function to update the section dropdown
  function updateSectionDropdown(sections) {
    const sectionDropdown = $('#sectionFilter');
    sectionDropdown.empty();

    // Add default option
    sectionDropdown.append('<option value="">All Sections</option>');

    // Add sections as options
    sections.forEach(section => {
      sectionDropdown.append(`<option value="${section}">${section}</option>`);
    });
  }
});
