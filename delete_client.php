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

// Comprobar si el ID del cliente fue enviado por POST
if (isset($_POST['id'])) {
    $client_id = $_POST['id'];

    // Consulta para eliminar el cliente de la base de datos
    $sql = "DELETE FROM clients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);

    if ($stmt->execute()) {
        // Si se ejecuta correctamente, respondemos con un éxito
        echo json_encode(['success' => true]);
    } else {
        // Si ocurre un error
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}

// Cerrar conexión
$conn->close();
?>
