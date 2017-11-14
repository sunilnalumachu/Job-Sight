<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSight</title>
    <link rel="stylesheet" href="stylesheets/main.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="shortcut icon" href="/myIcon.ico" type="image/x-icon">
    <link rel="icon" href="/myIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6" id="map"></div>
            <div class="col-md-6" id="jobs">
                <!--INSERT DATABASE QUERY RESULTS INTO JOB LIST DIVISION-->
  							<?php
  							$job = $_POST["job"];
  							$location = $_POST["location"];

  							if (isset($location)) {
  								$conn = new mysqli("153.91.152.245", "candrews", "C$4920.project", "CS4920_CAndrews");

  								if ($conn->connect_error) {
  									die("Connection failed: " . $conn->connect_error);
  								}

                  $stmt = $conn->prepare("SELECT employer.name, employer.address, employer.city, employer.state, employer.zipcode,
                                                 employer.phone_number, job.title, job.description, job.requirements,
                                                 job.qualifications, job.job_type, job.salary
                                          FROM employer
                                          INNER JOIN job ON employer.employer_id = job.employer_id
                                          WHERE employer.city = ?");
		  						$stmt->bind_param("s", $location);
		  						$stmt->execute();
		  						$stmt->bind_result($name, $address, $city, $state, $zipcode, $phone_number, $title, $description, $requirements, $qualifications, $jobtype, $salary);

		  						while($stmt->fetch()) {
											$areaCode = substr($phone_number, 0, 3);
								      $nextThree = substr($phone_number, 3, 3);
								      $lastFour = substr($phone_number, 6, 4);
								      $phone_number = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
                      if($salary == '') {
                        $salary = 'Depends on Experience';
                      } else {
                        $salary = '$' . number_format($salary);
                      }

		  								echo "<div class='company-details'><span style='text-align:center;'><h3>" . $name . "</h3><h5>" .
		                          $address . ",  " . $city . ", " . $state . " " . $zipcode .
		                          "</h5><h5>" . $phone_number . "</h5></span><p><b>Job Title:</b> " . $title . "</p><p><b>Description:</b> " .
                              $description . "</p><p><b>Requirements:</b> " . $requirements . "</p><p><b>Qualifications:</b> " . $qualifications .
                              "</p><p><b>Job Type:</b> " . $jobtype . "</p><p><b>Salary:</b> " . $salary . "</p><input type='button' style='display:block; margin: 0 auto' value='Apply To Job'><br></div><hr>";
		  						}

  								$stmt->close();
  								$conn->close();
  							}
  							?>
            </div>
        </div>
    </div>

    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: new google.maps.LatLng(39.0770801, -94.5344516),
                styles: [
                  {
                      "featureType": "administrative",
                      "elementType": "labels.text.fill",
                      "stylers": [
                          {
                              "color": "#444444"
                          }
                      ]
                  },
                  {
                      "featureType": "landscape",
                      "elementType": "all",
                      "stylers": [
                          {
                              "color": "#f2f2f2"
                          }
                      ]
                  },
                  {
                      "featureType": "poi",
                      "elementType": "all",
                      "stylers": [
                          {
                              "visibility": "off"
                          }
                      ]
                  },
                  {
                      "featureType": "road",
                      "elementType": "all",
                      "stylers": [
                          {
                              "saturation": -100
                          },
                          {
                              "lightness": 45
                          }
                      ]
                  },
                  {
                      "featureType": "road.highway",
                      "elementType": "all",
                      "stylers": [
                          {
                              "visibility": "simplified"
                          }
                      ]
                  },
                  {
                      "featureType": "road.arterial",
                      "elementType": "labels.icon",
                      "stylers": [
                          {
                              "visibility": "off"
                          }
                      ]
                  },
                  {
                      "featureType": "transit",
                      "elementType": "all",
                      "stylers": [
                          {
                              "visibility": "off"
                          }
                      ]
                  },
                  {
                      "featureType": "water",
                      "elementType": "all",
                      "stylers": [
                          {
                              "color": "#46bcec"
                          },
                          {
                              "visibility": "on"
                          }
                      ]
                  }
              ]
            });
            downloadUrl('http://localhost/SeniorProject/mapmarkers.php', function(data) {
              var xml = data.responseXML;
              var markers = xml.documentElement.getElementsByTagName('marker');
              Array.prototype.forEach.call(markers, function(markerElem) {
                var id = markerElem.getAttribute('id');
                var point = new google.maps.LatLng(
                    parseFloat(markerElem.getAttribute('lat')),
                    parseFloat(markerElem.getAttribute('lng')));
                var marker = new google.maps.Marker({
                  map: map,
                  position: point,
                });
              });
            });
          }
          function downloadUrl(url,callback) {
             var request = window.ActiveXObject ?
                 new ActiveXObject('Microsoft.XMLHTTP') :
                 new XMLHttpRequest;

             request.onreadystatechange = function() {
               if (request.readyState == 4) {
                 request.onreadystatechange = doNothing;
                 callback(request, request.status);
               }
             };

             request.open('GET', url, true);
             request.send(null);
          }
          function doNothing() {}
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2Nuz1E4iDrDfEbGhKMV3F0lYDhZmQd6w&callback=initMap">
    </script>
</body>

</html>
