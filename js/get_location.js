
(function($) {

// Get location using HTML5 Geo capability.
function get_loc() {
  pos = {}

  if ('geolocation' in navigator) {
    navigator.geolocation.getCurrentPosition(function(position) {
      // Handle the Geolocation results.
      if (position && position.coords) { 
        pos.lat = position.coords.latitude;
        pos.lon = position.coords.longitude;
      }
      console.log(pos.lat);
      console.log(pos.lon);

      // Submit the form.
      $.ajax({
        url: location.href,
        type: 'post',
        data: "wpcheckin_loc_submitted=Y&wpcheckin_lat=" + pos.lat + "&wpcheckin_lon=" + pos.lon,
      });
    }); 
  }
}

get_loc();

})(jQuery);
