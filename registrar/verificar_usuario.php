<?php
require '../database/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$correo = $data['correo'];

$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM Usuario WHERE username = ? OR correo = ?");
$stmt->execute([$username, $correo]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['exists' => $row['count'] > 0]);
?>
