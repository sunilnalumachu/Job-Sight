<?php

function parseToXML($htmlStr)
{
  $xmlStr=str_replace('<','&lt;',$htmlStr);
  $xmlStr=str_replace('>','&gt;',$xmlStr);
  $xmlStr=str_replace('"','&quot;',$xmlStr);
  $xmlStr=str_replace("'",'&#39;',$xmlStr);
  $xmlStr=str_replace("&",'&amp;',$xmlStr);
  return $xmlStr;
}

// Opens a connection to a MySQL server
$conn = new mysqli("153.91.152.245", "candrews", "C$4920.project", "CS4920_CAndrews");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Select all the rows in the markers table
$query = "SELECT * FROM markers ORDER BY employer_id";
$result = mysqli_query($conn, $query);

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = mysqli_fetch_assoc($result)){
  // Add to XML document node
  echo '<marker ';
  echo 'id="' . $row['employer_id'] . '" ';
  echo 'lat="' . $row['latitude'] . '" ';
  echo 'lng="' . $row['longitude'] . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';

?>
