
<?php
// Connect to the database
$host = "localhost";
$user = "root";
$password = "petsarefun";
$dbname = "testDB";

$conn = mysqli_connect($host, $user, $password, $dbname);

// Step 2: Check if the connection was successful
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Step 3: Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Step 4: Get the form data
  $id = $_POST["id"];
  $name = $_POST["name"];
  $amountTotal = $_POST["amountTotal"];
  $amountPortion = $_POST["amountPortion"];

  // Step 5: Construct the SQL query based on the form data
  $sql = "UPDATE test SET ";
  if (!empty($name)) {
    $sql .= "name='$name', ";
  }
  if (!empty($amountTotal)) {
    $sql .= "amountTotal='$amountTotal', ";
  }
  if (!empty($amountPortion)) {
    $sql .= "amountPortion='$amountPortion', ";
  }
  $sql = rtrim($sql, ", "); // Remove trailing comma
  $sql .= " WHERE id='$id'";

  // Step 6: Execute the query and check if it was successful
  if (mysqli_query($conn, $sql)) {
    // echo "Record updated successfully, redirecting back to pet entry!";
    echo "<script>alert('Record updated successfully.'); setTimeout(function(){window.location.href='dataentry.html';}, 200);</script>";
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
  
  
}

// Step 7: Close the database connection
mysqli_close($conn);

?>
