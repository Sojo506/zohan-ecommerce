<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=zohan_tech_store;charset=utf8mb4','root','CGJS2050',[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$pdo->beginTransaction();

$estadoActivo = (int)($pdo->query("SELECT ID_ESTADO FROM ESTADO_TB ORDER BY ID_ESTADO ASC LIMIT 1")->fetchColumn() ?: 1);

function getOrCreateMarca(PDO $pdo, string $nombre, int $estado): int {
    $q = $pdo->prepare("SELECT ID_MARCA FROM MARCA_TB WHERE NOMBRE = :n LIMIT 1");
    $q->execute([':n' => $nombre]);
    $id = (int)($q->fetchColumn() ?: 0);
    if ($id > 0) return $id;
    $i = $pdo->prepare("INSERT INTO MARCA_TB (NOMBRE, ID_ESTADO) VALUES (:n, :e)");
    $i->execute([':n' => $nombre, ':e' => $estado]);
    return (int)$pdo->lastInsertId();
}

function getOrCreateCategoria(PDO $pdo, string $nombre, int $estado): int {
    $q = $pdo->prepare("SELECT ID_CATEGORIA FROM CATEGORIA_TB WHERE NOMBRE = :n LIMIT 1");
    $q->execute([':n' => $nombre]);
    $id = (int)($q->fetchColumn() ?: 0);
    if ($id > 0) return $id;
    $i = $pdo->prepare("INSERT INTO CATEGORIA_TB (NOMBRE, ID_ESTADO) VALUES (:n, :e)");
    $i->execute([':n' => $nombre, ':e' => $estado]);
    return (int)$pdo->lastInsertId();
}

$gamingCategoryId = getOrCreateCategoria($pdo, 'Gaming', $estadoActivo);

$items = [
    [
        'name' => 'PLAYSTATION 5 (PS5) SLIM EDICION DIGITAL - FORNITE FLOWERING CHAOS',
        'brand' => 'Sony',
        'price' => 265000,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2025/12/CFI-2015-FORNITE-BUNDLE.webp',
        'description' => 'PS5 Slim Edicion Digital con lote Fortnite Caos Floreciente. Incluye contenido cosmetico exclusivo y 1000 paVos. SSD 825GB, retrocompatibilidad PS4 y ASTRO\'S Playroom.',
    ],
    [
        'name' => 'Lenovo Legion Go 8APU1 Ryzen Z1 8.8 16GB 512GB W11',
        'brand' => 'Lenovo',
        'price' => 279000,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2025/12/83E10011LM.webp',
        'description' => 'Consola portatil Legion Go con Ryzen Z1, 16GB RAM DDR5, SSD 512GB, pantalla 8.8, Wi-Fi, Bluetooth 5.3 y Windows 11.',
    ],
    [
        'name' => 'NINTENDO SWITCH 2 + POKEMON LEGENDS Z-A',
        'brand' => 'Nintendo',
        'price' => 345000,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2025/11/BEE-001.webp',
        'description' => 'Pack Nintendo Switch 2 con Pokemon Legends Z-A. Pantalla 7.9 1080p, base con salida 4K, Joy-Con 2 magneticos y almacenamiento interno de 256GB.',
    ],
    [
        'name' => 'Control inalambrico DualSense PS5 - CHROMA INDI',
        'brand' => 'Sony',
        'price' => 37500,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2024/12/CFI-ZCT1W-1000044152.jpg',
        'description' => 'Control DualSense para PS5 con respuesta haptica, gatillos adaptativos, microfono integrado y conector de 3.5mm.',
    ],
    [
        'name' => 'CONTROL INALAMBRICO DUALSENSE V2 PS5 - NEGRO',
        'brand' => 'Sony',
        'price' => 37500,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2026/03/CFI-ZCT2W-BLACK.webp',
        'description' => 'Mando DualSense V2 PS5 negro con conexion Bluetooth, vibracion, gatillos adaptativos, panel tactil y bateria recargable.',
    ],
    [
        'name' => 'SIMULADOR DD LOGITECH RS50 SYSTEM + PEDALES RS - PC-PS4-PS5',
        'brand' => 'Logitech',
        'price' => 465000,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2025/12/RS-PEDALS.webp',
        'description' => 'Sistema Logitech RS50 con accionamiento directo hasta 8 Nm, TRUEFORCE, volante y pedales. Compatible con PC, PS4 y PS5.',
    ],
];

$selProd = $pdo->prepare("SELECT ID_PRODUCTO FROM PRODUCTO_TB WHERE NOMBRE = :n LIMIT 1");
$insProd = $pdo->prepare("INSERT INTO PRODUCTO_TB (NOMBRE, DESCRIPCION, PRECIO, ID_CATEGORIA, ID_MARCA, ID_ESTADO) VALUES (:n,:d,:p,:c,:m,:e)");
$updProd = $pdo->prepare("UPDATE PRODUCTO_TB SET DESCRIPCION=:d, PRECIO=:p, ID_CATEGORIA=:c, ID_MARCA=:m, ID_ESTADO=:e WHERE ID_PRODUCTO=:id");
$selImg = $pdo->prepare("SELECT ID_IMAGEN FROM PRODUCTO_IMAGE_TB WHERE ID_PRODUCTO = :id LIMIT 1");
$insImg = $pdo->prepare("INSERT INTO PRODUCTO_IMAGE_TB (ID_PRODUCTO, URL_IMAGE, ID_ESTADO) VALUES (:id,:u,:e)");
$updImg = $pdo->prepare("UPDATE PRODUCTO_IMAGE_TB SET URL_IMAGE=:u, ID_ESTADO=:e WHERE ID_IMAGEN=:i");

$created = 0;
$updated = 0;

foreach ($items as $item) {
    $marcaId = getOrCreateMarca($pdo, $item['brand'], $estadoActivo);

    $selProd->execute([':n' => $item['name']]);
    $prodId = (int)($selProd->fetchColumn() ?: 0);

    if ($prodId > 0) {
        $updProd->execute([
            ':d' => $item['description'],
            ':p' => $item['price'],
            ':c' => $gamingCategoryId,
            ':m' => $marcaId,
            ':e' => $estadoActivo,
            ':id' => $prodId,
        ]);
        $updated++;
    } else {
        $insProd->execute([
            ':n' => $item['name'],
            ':d' => $item['description'],
            ':p' => $item['price'],
            ':c' => $gamingCategoryId,
            ':m' => $marcaId,
            ':e' => $estadoActivo,
        ]);
        $prodId = (int)$pdo->lastInsertId();
        $created++;
    }

    $selImg->execute([':id' => $prodId]);
    $imgId = (int)($selImg->fetchColumn() ?: 0);
    if ($imgId > 0) {
        $updImg->execute([':u' => $item['image'], ':e' => $estadoActivo, ':i' => $imgId]);
    } else {
        $insImg->execute([':id' => $prodId, ':u' => $item['image'], ':e' => $estadoActivo]);
    }
}

// Imagen solicitada para producto que estaba sin imagen definitiva
$fix = $pdo->prepare("UPDATE PRODUCTO_IMAGE_TB i JOIN PRODUCTO_TB p ON p.ID_PRODUCTO = i.ID_PRODUCTO SET i.URL_IMAGE = :u, i.ID_ESTADO=:e WHERE p.NOMBRE = 'Laptop Gigabyte G5 KC RTX 3060'");
$fix->execute([
    ':u' => 'https://img.pacifiko.com/PROD/resize/1/1000x1000/NzYxZjg4N2_3.jpg',
    ':e' => $estadoActivo,
]);

$pdo->commit();

echo "Gaming insertados/actualizados. Creados: {$created}, Actualizados: {$updated}\n";