<?php
require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/Database.php';

Env::load(__DIR__ . '/../.env');
$pdo = Database::connection();

function ensurePromotion(PDO $pdo, string $name, float $percent): int
{
    $stmt = $pdo->prepare("SELECT ID_PROMOCION FROM PROMOCION_TB WHERE NOMBRE = :name AND PORCENTAJE = :pct LIMIT 1");
    $stmt->execute([':name' => $name, ':pct' => $percent]);
    $id = $stmt->fetchColumn();
    if ($id) {
        return (int)$id;
    }

    $stmt = $pdo->prepare("INSERT INTO PROMOCION_TB (NOMBRE, DESCRIPCION, PORCENTAJE, FECHA_INICIO, FECHA_FIN, ID_ESTADO)
        VALUES (:name, :desc, :pct, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 60 DAY), 1)");
    $stmt->execute([
        ':name' => $name,
        ':desc' => 'Promocion automatica',
        ':pct' => $percent,
    ]);
    return (int)$pdo->lastInsertId();
}

function findProductId(PDO $pdo, string $pattern): ?int
{
    $stmt = $pdo->prepare("SELECT ID_PRODUCTO FROM PRODUCTO_TB WHERE LOWER(NOMBRE) LIKE :pat ORDER BY ID_PRODUCTO DESC LIMIT 1");
    $stmt->execute([':pat' => strtolower($pattern)]);
    $id = $stmt->fetchColumn();
    return $id ? (int)$id : null;
}

function attachPromo(PDO $pdo, int $promoId, int $productId): void
{
    $stmt = $pdo->prepare("SELECT 1 FROM PROMOCION_PRODUCTO_TB WHERE ID_PROMOCION = :promo AND ID_PRODUCTO = :prod LIMIT 1");
    $stmt->execute([':promo' => $promoId, ':prod' => $productId]);
    if ($stmt->fetchColumn()) {
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO PROMOCION_PRODUCTO_TB (ID_PROMOCION, ID_PRODUCTO, ID_ESTADO)
        VALUES (:promo, :prod, 1)");
    $stmt->execute([':promo' => $promoId, ':prod' => $productId]);
}

$targets = [
    ['pattern' => '%su650%', 'percent' => 20, 'name' => 'Promo SU650 20'],
    ['pattern' => '%caddy%', 'percent' => 30, 'name' => 'Promo Caddy 30'],
    ['pattern' => '%rs50%', 'percent' => 44, 'name' => 'Promo RS50 44'],
    ['pattern' => '%dualsense v2%', 'percent' => 47, 'name' => 'Promo DualSense 47'],
];

$updated = [];
foreach ($targets as $target) {
    $productId = findProductId($pdo, $target['pattern']);
    if (!$productId) {
        echo "No se encontro producto para patron: {$target['pattern']}" . PHP_EOL;
        continue;
    }

    $promoId = ensurePromotion($pdo, $target['name'], $target['percent']);
    attachPromo($pdo, $promoId, $productId);
    $updated[] = $productId;
}

echo "Promos asignadas a productos: " . implode(', ', $updated) . PHP_EOL;
