<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "care4purrt_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Example query to fetch pets
$sql = "SELECT * FROM pets";
$result = mysqli_query($conn, $sql);

// Display pets
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "Pet Name: " . $row["name"]. " - Age: " . $row["age"]. " - Species: " . $row["species"]. "<br>";
    }
} else {
    echo "0 results";
}

// Close connection
mysqli_close($conn);
?>
