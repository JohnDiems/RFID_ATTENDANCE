$(document).ready(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('.dot').on('click', function() {
    var dot = $(this);
    var status = dot.data('status') === 'Active' ? 0 : 1;
    var profileId = dot.data('profile-id');
    
    if (confirm('Are you sure to change status?')) {
      $.ajax({
        type: 'POST',
        url: 'status/' + profileId,
        data: { status: status },
        success: function(response) {
          if (status === 1) {
            dot.removeClass('pe').addClass('de');
            dot.data('status', 'Active');
            dot.siblings('.status-text').text('Active');
          } else {
            dot.removeClass('de').addClass('pe');
            dot.data('status', 'Inactive');
            dot.siblings('.status-text').text('Inactive');
          }
        },
        error: function(xhr, textStatus, errorThrown) {
          // Handle error here
          console.log(errorThrown);
        }
      });
    }
  });
});

