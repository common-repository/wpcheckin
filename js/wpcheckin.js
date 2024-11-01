jQuery(document).ready(function($) {
  $('#wpcheckin_places_list').hide();

  pos = {}
  this.get_loc = function() {
    if ('geolocation' in navigator) {
      navigator.geolocation.getCurrentPosition(function(position) {
        // Handle the Geolocation results.
        if (position && position.coords) { 
          pos.lat = position.coords.latitude;
          pos.lon = position.coords.longitude;
        }
        console.log(pos.lat);
        console.log(pos.lon);

        $.ajax({
          url: ajax_url,
          type: 'post',
          data: "wpcheckin_lat=" + pos.lat + "&wpcheckin_lon=" + pos.lon + "&action=wpcheckin_refresh&wpcheckin_places_submitted=Y",
          success: function(html) {
            console.log('Ajax refresh complete...');
            $('#wpcheckin_loc_place').prepend(html);
          }
        });

      });
    }
  }

  var self = this;

  $('#wpcheckin_get_places').on('click', function(event) {
    event.preventDefault();

    self.get_loc();
    $('#wpcheckin_places_list').show();
    $('#wpcheckin_get_places').hide();
  });


  // Create checkin post.
  $(document.body).on('click', '.wpcheckin', function(event) {
    event.preventDefault();
    //console.log(event);
    //console.log($('#form_' + event.target.id).serialize());

    // Send the location data to the wpcheckin_post_checkin function.
    $.ajax({
      url: ajax_url,
      type: 'post',
      data: $('#form_' + event.target.id).serialize(),
      success: function(html) {
        console.log('Ajax post complete...');
        $('#wpcheckin_places_list').hide();
        $('#wpcheckin_checkin_list').prepend(html);

        if ($('#wpcheckin_no').html() != undefined) {
          $('#wpcheckin_no').remove();
        }
      }
    });

  });

});
