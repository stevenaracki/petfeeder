<?php
			// Step 1: Establish a connection to the database
			$host = "localhost";
			$user = "root";
			$password = "petsarefun";
			$dbname = "testDB";
			$conn = new mysqli($host, $user, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			// Step 2: Execute the SELECT statement and store the result set
			$sql = "SELECT * FROM test";
			$result = $conn->query($sql);

			// Step 3: Display the result set in an HTML table
			while ($row = mysqli_fetch_array($result)) {
        // Step 4: Display the data
        foreach ($row as $key => $value) {
          if ($key == "name"){
            echo "$value<br>";
          }
          if (preg_match('/^_[0-9]{4}$/', $key)) {
            if (preg_match('/_01/', $key))
            echo "January " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_02/', $key))
            echo "February " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_03/', $key))
            echo "March " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_04/', $key))
            echo "April " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_05/', $key))
            echo "May " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_06/', $key))
            echo "June " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_07/', $key))
            echo "July " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_08/', $key))
            echo "August " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_10/', $key))
            echo "October " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_11/', $key))
            echo "November " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
            if (preg_match('/_12/', $key))
            echo "December " . substr($key, -2) . ": " . $value/2 . " Cups"."<br>";
          }
          }
        echo "<hr>";
    }

			// Step 4: Close the database connection
			$conn->close();
		?>