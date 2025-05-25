<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = ""; // Cambia si es necesario
$db = "tienda_deportiva";

$conn = new mysqli($host, $user, $password, $db);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

// Consulta
$sql = "
    SELECT 
        p.id_producto,
        p.nombre,
        p.descripcion,
        p.precio,
        p.imagen_url,
        c.nombre AS categoria,
        m.nombre AS marca
    FROM 
        productos p
    JOIN 
        categorias c ON p.id_categoria = c.id_categoria
    JOIN 
        marcas m ON p.id_marca = m.id_marca
    WHERE 
        c.nombre = 'Mujer';
";

$result = $conn->query($sql);

// Preparar respuesta
$productos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Devolver JSON
echo json_encode($productos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
?>
