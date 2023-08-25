<?php
// Step 1: Connect to MySQL database
$host = "localhost";
$user = "root";
$password = "petsarefun";
$dbname = "testDB";

$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Step 2: Retrieve all values of the id column
$sql = "SELECT id FROM test";
$result = mysqli_query($conn, $sql);

// Step 3: Store the values in an array
$id_array = array();
if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $id_array[] = $row['id'];
  }
}

// Step 4: Create an HTML form with a dropdown selection element
?>
<form action="update.php" method="post">
  <label for="id">Select an ID:</label>
  <select id="id" name="id">
  
  <?php
  // Step 5: Populate the dropdown selection element with the values from the array
  foreach ($id_array as $id) {
    echo "<option value=\"$id\">$id</option>";
  }
  ?>
  <input type="text" name="name" placeholder="Pet Name">
  <input type="text" name="amountTotal" placeholder="Cups Per Day">
  <input type="text" name="amountPortion" placeholder="Cups Per Feeding">
  <input type="submit" value="Submit">
  </select>
  <br><br>
</form>

<?php
// Close MySQL connection
mysqli_close($conn);
?>