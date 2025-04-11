// THIS STUDENTLIST/MONITORING/SECTIONLIST/USER DATA TABLE
$(document).ready(function() {
  var table = $('#filter').DataTable({
    // REMOVE THE SHOWING ENTRIES ON DATATABLE
    "oLanguage": {
      "sInfo": "",
    },
    // Enable sorting on the first column (NAME)
    "aoColumnDefs": [
      { 'bSortable': true, 'aTargets': [0] }
    ],
    // Initial sorting by the first column in ascending order
    "order": [[0, 'asc']],
  });

  $('#roleFilter').on('change', function(){
    var SelectRole = $(this).val();
    // Clear the previous search
    table.search('').draw();

    // Apply the new search based on the selected role
    if (SelectRole && SelectRole !== 'all') {
      table.columns(4).search(SelectRole).draw();
    }else {
      // If 'Select Role' or nothing is selected, show all records
      table.search('').columns().search('').draw();
  }

  });

  

  var table1 = $('#filterDashboard').DataTable({
    // REMOVE THE SHOWING ENTRIES ON DATATABLE
    "oLanguage": {
      "sInfo": "",
    },
    // Enable sorting on the first column (NAME)
    "aoColumnDefs": [
      { 'bSortable': true, 'aTargets': [0] }
    ],
    // Initial sorting by the first column in ascending order
    "order": [[2, 'asc']],
  });
  
  // REMOVE THE SEARCH LEBEL FORM DATATABLE WRAPPER
  $('.dataTables_filter label').contents().filter(function() {
    return this.nodeType === 3;
  }).remove();

  // PLACEHOLDER SEARCH INPUT  ---> SEARCH
  table.columns().every(function() {
    var column = this;
    $(`input[type="search"]`, column.footer()).attr('placeholder', 'Search');
  });
});


// THIS DASHBAORD RECENTUSER DATA TABLE
$(document).ready(function() {
  var recentuser = $('#recentUsersFilter').DataTable({
    // REMOVE THE SHOWING ENTRIES ON DATATABLE
    "oLanguage": {
      "sInfo": "",
    },
    // Enable sorting on the first column (NAME)
    "aoColumnDefs": [
      { 'bSortable': true, 'aTargets': [1] }
    ],
    // Initial sorting by the first column in ascending order
    "order": [[1, 'asc']],
  });
  
  // REMOVE THE SEARCH LEBEL FORM DATATABLE WRAPPER
  $('.dataTables_filter label').contents().filter(function() {
    return this.nodeType === 3;
  }).remove();
  
  // PLACEHOLDER SEARCH INPUT  ---> SEARCH
  recentuser.columns().every(function() {
    var column = this;
    $(`input[type="search"]`, column.footer()).attr('placeholder', 'Search');
  });
});
