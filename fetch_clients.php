<?php
// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adriano_login";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener todos los clientes
$sql = "SELECT id, name, surnames, company, image_url FROM clients";
$result = $conn->query($sql);

// Verificar si hay resultados
$clients = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clients[] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "surnames" => $row["surnames"],
            "company" => $row["company"],
            "image_url" => $row["image_url"]
        ];
    }
}

// Enviar los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($clients);

// Cerrar conexión
$conn->close();
?>
