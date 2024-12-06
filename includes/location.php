<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: login.php");
    exit;
}

// Handle the POST request to save the location data if necessary
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // You can save the location in the database or session as needed
    // Example: Save to the user's session
    $_SESSION['user_location'] = ['latitude' => $latitude, 'longitude' => $longitude];

    echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Location saved successfully.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
</head>
<body>
    <h3>Request Location</h3>

    <button onclick="getLocation()">Allow Location Access</button>

    <script>
        // Function to request the user's location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;

                    // Send location data to PHP via a POST request
                    fetch('location.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'latitude=' + latitude + '&longitude=' + longitude
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Show the response from the PHP server
                        alert('Location saved: ' + data);
                    })
                    .catch(error => {
                        alert('Error: ' + error);
                    });
                }, function(error) {
                    alert('Error getting location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
    </script>
</body>
</html>
