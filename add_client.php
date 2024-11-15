<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adriano_login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$name = $_POST['name'];
$surnames = $_POST['surnames'];
$company = $_POST['company'];
$image_url = $_POST['image_url'];

$sql = "INSERT INTO clients (name, surnames, company, image_url) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $surnames, $company, $image_url);

if ($stmt->execute()) {
    echo json_encode([
        "id" => $conn->insert_id,
        "name" => $name,
        "surnames" => $surnames,
        "company" => $company,
        "image_url" => $image_url
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Error al agregar el cliente"]);
}

$stmt->close();
$conn->close();
?>
