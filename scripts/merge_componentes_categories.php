<?php
require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/Database.php';

Env::load(__DIR__ . '/../.env');
$pdo = Database::connection();

$targetName = 'Componentes';
$targetStmt = $pdo->prepare("SELECT ID_CATEGORIA FROM CATEGORIA_TB WHERE LOWER(REPLACE(NOMBRE,' ','')) = LOWER(REPLACE(:name,' ','')) LIMIT 1");
$targetStmt->execute([':name' => $targetName]);
$targetId = $targetStmt->fetchColumn();

if (!$targetId) {
    $insert = $pdo->prepare("INSERT INTO CATEGORIA_TB (NOMBRE, ID_ESTADO) VALUES (:name, 1)");
    $insert->execute([':name' => $targetName]);
    $targetId = $pdo->lastInsertId();
}

$targetId = (int)$targetId;

$aliases = [
    'almacenamiento',
    'fuentes de poder',
    'placas madre',
    'refrigeracion',
    'refrigeración',
    'tarjeta grafica',
    'tarjeta gráfica',
];

$normalized = array_map(function ($name) {
    return str_replace(' ', '', strtolower($name));
}, $aliases);

$placeholders = implode(',', array_fill(0, count($normalized), '?'));

// Mover productos de esas categorias a "Componentes"
$sqlUpdate = "UPDATE PRODUCTO_TB p
    JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
    SET p.ID_CATEGORIA = ?
    WHERE LOWER(REPLACE(c.NOMBRE, ' ', '')) IN ($placeholders)";

$params = array_merge([$targetId], $normalized);
$stmt = $pdo->prepare($sqlUpdate);
$stmt->execute($params);

echo 'Productos actualizados a Componentes: ' . $stmt->rowCount() . PHP_EOL;

