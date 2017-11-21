<script>
function foodEstablishmentMap() {

  maps = new google.maps.Map(document.getElementById('foodCarparkMap'), {
    zoom: 16,
    center: {lat: <?php echo $lat ?>, lng: <?php echo $long ?>}
  });

  addRestaurantMarker({lat: <?php echo $lat ?>, lng: <?php echo $long ?>}, 'restaurant Name');

  <?php
  $max2 = sizeof($carparkLatArray);
  for($j=0; $j < $max2; $j++) {
    ?>
    addCarparkMarker({lat: <?php echo $carparkLatArray[$j] ?>, lng: <?php echo $carparkLongArray[$j] ?>});
    <?php
  }
  ?>

  //Add carpark marker function
  function addCarparkMarker(coords, carparkDetails) {
    var marker = new google.maps.Marker({
      position:coords,
      map:maps,
      icon: "images/carpark.png"
    });


  }

  //Add restaurant marker function
  function addRestaurantMarker(coords, restuarantDetails) {
    var marker = new google.maps.Marker({
      position:coords,
      map:maps,
      icon: "images/restaurant.png"
    });
  }

}

google.maps.event.addDomListener(window, 'load', foodEstablishmentMap);
</script>
