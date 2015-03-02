<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>multtable.php</title>
</head>
<body>
<?php

clearstatcache();

// The code for connecting to my database I got from lecture
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","afzals-db","EUhwv7xKbCMM3XOm","afzals-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error ".$mysqli->connect_errno."".$mysqli->connect_error;
	}
else{
	echo "Connected to server<br>";
}

$server = htmlspecialchars($_SERVER["PHP_SELF"]);
echo "Add video to database by filling in the following fields and clicking submit";
echo '<form method="post" action="'. $server . '">';
echo '<input type="text" name="name">Name<br>';
echo '<input type="text" name="category">Category<br>';
echo '<input type="number" name="length">Length<br>';
echo '<input type="submit"><br>';

$name = "";
$category = "";
$length = 0;

// The strategy for cleaning up the inputs came from http://www.w3schools.com/php/php_form_validation.asp
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(empty($_POST["name"]) || is_numeric($_POST["name"])){	
	echo $_POST["name"]. " Invalid input for name";
	echo '<a href ="http://web.engr.oregonstate.edu/~afzals/video.php">Click Here to restart</a>';
  }
  else{
	$name = clean_input($_POST["name"]);
  }
  if(empty($_POST["category"]) || is_numeric($_POST["category"])){	
	echo $_POST["category"]. " Invalid input for category";
	echo '<a href ="http://web.engr.oregonstate.edu/~afzals/video.php">Click Here to restart</a>';
  }
  else{
	$category = clean_input($_POST["category"]);
  }
  if(empty($_POST["length"]) || !is_numeric($_POST["length"])){	
	echo $_POST["length"]. " Invalid input for length";
	echo '<a href ="http://web.engr.oregonstate.edu/~afzals/video.php">Click Here to restart</a>'; 
  }
  else{
	$length = clean_input($_POST["length"]);
  }
}



function clean_input($key) {
  $key = trim($key);
  $key = stripslashes($key);
  $key = htmlspecialchars($key);
  return $key;
}

$insert = "INSERT INTO video_inventory(name, category, length) VALUES ('". $name. "', '" . $category. "', '" . $length. "')";
if ($mysqli->query($insert) === TRUE) {
    echo "New entry successful";
} else {
    echo "Error: " . $insert . "<br>" . $mysqli->error;
}

$query = "SELECT name, category, length, rented FROM video_inventory";
$result = mysqli_query($mysqli, $query);

// output data table
echo '<table border=1>';
echo '<br>';
echo '<tr>';
echo '<td>Name<td>Category<td>Length<td>Rented';

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
      echo '<tr>';
	  echo '<td>'. $row["name"]. '<td>' . $row["category"]. '<td>'. $row["length"]. '<td>' . $row["rented"]. '<br>';
    }
} 
else {
    echo "0 results";
}
echo '</table>';
$mysqli->close();
?>
</body>
</html>

