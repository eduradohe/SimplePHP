<?php
echo "<h1>Olá Mundo v2.0 com db</h1> ";
echo $_SERVER['SERVER_ADDR'];

$conn = new mysqli("mysql", "admin", "redhat@123", "sampledb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT nome FROM cidade");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<br>" . $row["nome"];
    }
} else {
    echo "0 results";
}
$conn->close();
?>
