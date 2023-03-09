<style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 420px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
</style>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div id="map"></div>
    </div>
  </div>
</div>

<script>
    function myNavFunc(lat,lon){
        // If it's an iPhone..
        if( (navigator.platform.indexOf("iPhone") != -1) 
            || (navigator.platform.indexOf("iPod") != -1)
            || (navigator.platform.indexOf("iPad") != -1))
            window.open("maps://www.google.com/maps/dir/?api=1&travelmode=driving&layer=traffic&destination="+lat+","+lon);
        else
            window.open("https://www.google.com/maps/dir/?api=1&travelmode=driving&layer=traffic&destination="+lat+","+lon);
    }
$(".open_map").click(function(){
    var _lat = $(this).data("lat");
    var _lon = $(this).data("lon");
    var _label = $(this).data("label");
    
    open_map(_lat,_lon,_label);
});
function open_map(_lat,_lon,_label){
    $(".bd-example-modal-lg").modal("toggle");
    $('.bd-example-modal-lg').on('shown.bs.modal', function (e) {
    // do something...
        setMarket(_lat,_lon,_label);
    })
}
var map;
var marker;
function setMarket(_lat,_lon,_label){
    
    var latlng = new google.maps.LatLng(parseFloat(_lat), parseFloat(_lon));
    marker.setPosition(latlng);
    map.panTo(marker.position);
}
// Initialize and add the map
function initMap() {
    // The location of Uluru
    var uluru = {
        lat: parseFloat('24.774265'),
        lng: parseFloat('46.738586')
    };
    // The map, centered at Uluru
    map = new google.maps.Map(
        document.getElementById('map'), {
            zoom: 12,
            center: uluru
        });
        marker = new google.maps.Marker({
            position: uluru,
            map: map
          });
   
}
</script>
<!--Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial)
    * The callback parameter executes the initMap() function
    -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option("google_api_key"); ?>&callback=initMap">
</script>