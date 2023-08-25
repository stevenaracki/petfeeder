<?php
// Connect to the database
$host = "localhost";
$user = "root";
$password = "petsarefun";
$dbname = "testDB";

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Retrieve data from the "test" table
$sql = "SELECT * FROM test";
$result = mysqli_query($conn, $sql);

// Display the data in an HTML table
if (mysqli_num_rows($result) > 0) {
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th><th>Cups Per Day</th><th>Cups Per Feeding</th></tr>";
	while ($row = mysqli_fetch_assoc($result)) {
		echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['name'] . '</td>';
    $cupsPerDay = ($row['amountTotal'])/2;
		echo '<td>' . $cupsPerDay . '</td>';
    $cupsPerFeeding = ($row['amountPortion'])/2;
		echo '<td>' . $cupsPerFeeding . '</td>';
    echo '</tr>';
	}
	echo "</table>";
} else {
	echo "0 results";
}

mysqli_close($conn);
?>
