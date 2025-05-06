<?php
$pickup_lat = $_GET['pickup_lat'] ?? 0;
$pickup_lng = $_GET['pickup_lng'] ?? 0;
$dest_lat = $_GET['dest_lat'] ?? 0;
$dest_lng = $_GET['dest_lng'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driving Route - Shortest Path</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            color: #092448;
        }
        #map {
            height: 600px;
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        #info {
            text-align: center;
            font-size: 18px;
            color: #092448;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Driving Route For Car(Pickup → Destination)</h2>
<div id="info">Loading distance & duration...</div>
<div id="map"></div>

<script>
    const pickup = { lat: parseFloat("<?= $pickup_lat ?>"), lng: parseFloat("<?= $pickup_lng ?>") };
    const destination = { lat: parseFloat("<?= $dest_lat ?>"), lng: parseFloat("<?= $dest_lng ?>") };

    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14,
            center: pickup,
        });

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: false,
            polylineOptions: {
                strokeColor: "#092448",
                strokeWeight: 5,
            }
        });

        const request = {
            origin: pickup,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        };

        directionsService.route(request, function(result, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);

                const route = result.routes[0].legs[0];
                const distance = route.distance.text;
                const duration = route.duration.text;

                document.getElementById("info").innerHTML = 
                    `Distance: <strong>${distance}</strong> | Estimated Duration: <strong>${duration}</strong>`;
            } else {
                document.getElementById("info").innerText = "Unable to find driving route: " + status;
            }
        });
    }
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDumdDv9jxmpC0yaURPXnqkk4kssB8R3C4&callback=initMap">
</script>

</body>
</html>
