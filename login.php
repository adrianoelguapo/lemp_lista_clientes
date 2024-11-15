<?php
// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adriano_login";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear base de datos si no existe
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);

// Seleccionar la base de datos
$conn->select_db($dbname);

// Crear tabla de usuarios si no existe
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// Verificar si el usuario 'admin' ya existe
$defaultUser = "admin";
$defaultPassword = "admin123";

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $defaultUser);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insertar usuario predeterminado solo si no existe
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $defaultUser, $defaultPassword);
    $stmt->execute();
}

// Crear tabla de clientes si no existe
$sql = "CREATE TABLE IF NOT EXISTS clients (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255) NOT NULL,
    name VARCHAR(50) NOT NULL,
    surnames VARCHAR(100) NOT NULL,
    company VARCHAR(100) NOT NULL
)";
$conn->query($sql);

// Insertar datos predeterminados en la tabla clients si está vacía
$sql = "SELECT COUNT(*) AS count FROM clients";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    // Datos predeterminados de los clientes
    $clients = [
        ["https://thumbs.wbm.im/pw/small/6dc1cb1116b972bb2405441d4d590cd2.jpg", "Juan", "Sanchez García", "Sonorix"],
        ["https://mrwallpaper.com/images/high/portrait-of-woman-with-random-people-5wu04gyr7p6p0i5c.jpg", "Maria", "Lopez Fernandez", "TechCorp"],
        ["https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTV4UlS1Ehv87B7_HRdQWlKz8Jw13A0zxuiuQ&s", "Carlos", "Ramirez Diaz", "InnovateX"],
        ["https://images.pexels.com/photos/6413585/pexels-photo-6413585.jpeg", "Ana", "Martinez Rubio", "DesignPro"],
        ["https://t4.ftcdn.net/jpg/08/25/97/89/360_F_825978932_lbb5oSb9Ylvt2P6Rc2aBxcu7IaYyZw8j.jpg", "Pedro", "Gonzalez Vega", "BuildIt"],
        ["https://thumbs.wbm.im/pw/small/0a8923b642859e78e6bde0b0f05574f5.jpg", "Laura", "Fernandez Gutierrez", "Healthify"],
        ["https://t3.ftcdn.net/jpg/07/59/66/74/360_F_759667428_Yh4VTraap3sDny1zeSTo0PejLGXlKyLk.jpg", "Miguel", "Hernandez Romero", "EcoSolutions"],
        ["https://media.istockphoto.com/id/1226087571/photo/freckled-caucasian-entrepreneur-with-red-hair-and-eyeglasses-is-having-a-phone-discussion.jpg?s=612x612&w=0&k=20&c=t_JpzVYc77-IEgZ5Wg3z7rULFORY5eiYt437zKrcNRg=", "Sara", "Garcia Ramos", "SmartHub"],
        ["https://img.freepik.com/premium-photo/man-smiling-alleyway_148840-84975.jpg", "Luis", "Perez Sanz", "AgriGrow"],
        ["https://media.istockphoto.com/id/1489681030/photo/hispanic-girl-is-making-selfie-on-smartphone-woman-is-relaxing-and-using-mobile-phone-camera.jpg?s=612x612&w=0&k=20&c=91b71D9D6ewQd9g7LGnRistL0oITyK2vW-hkbij8h4M=", "Elena", "Gomez Ruiz", "FinTech4U"]
    ];

    // Insertar datos en la tabla clients
    foreach ($clients as $client) {
        $sql = "INSERT INTO clients (image_url, name, surnames, company) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $client[0], $client[1], $client[2], $client[3]);
        $stmt->execute();
    }
}

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar usuario y contraseña
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirigir a clientes.html en caso de login exitoso
        header("Location: clientes.html");
        exit(); // Detener la ejecución para evitar que el código continúe
    } else {
        header("Location: index.html");
    }
    $stmt->close();
}

// Cerrar conexión
$conn->close();
?>