<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adriano_login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id = $_POST['id'];
$name = $_POST['name'];
$surnames = $_POST['surnames'];
$company = $_POST['company'];
$image_url = $_POST['image_url'];

$sql = "UPDATE clients SET name=?, surnames=?, company=?, image_url=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $name, $surnames, $company, $image_url, $id);

if ($stmt->execute()) {
    echo json_encode([
        "id" => $id,
        "name" => $name,
        "surnames" => $surnames,
        "company" => $company,
        "image_url" => $image_url
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar el cliente"]);
}

$stmt->close();
$conn->close();
?>
