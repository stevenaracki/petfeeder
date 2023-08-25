<?php
$name = $_POST["name"];
$body = $_POST["body"];
$priority = filter_input(INPUT_POST, "priority", FILTER_VALIDATE_INT);
$type = filter_input(INPUT_POST, "type", FILTER_VALIDATE_INT);
$RFID=$_POST["RFID"];



$host = "localhost";
$dbname = "testDB";
$username = "root";
$password = "petsarefun";

$conn = mysqli_connect($host, $username, $password, $dbname);


if (mysqli_connect_errno()) {
  die("Connection error: " . mysqli_connect_error());
}

$sql = "UPDATE test SET name='$name', body='$body', priority='$priority', type='$type' WHERE RFID=$RFID";
mysqli_query($conn, $sql);

// Close MySQL connection
mysqli_close($conn);

?>