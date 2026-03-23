<?php
require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/Database.php';

Env::load(__DIR__ . '/../.env');
$pdo = Database::connection();

function ensureCategory(PDO $pdo, string $name): int
{
    $stmt = $pdo->prepare("SELECT ID_CATEGORIA FROM CATEGORIA_TB WHERE LOWER(NOMBRE) = LOWER(:name) LIMIT 1");
    $stmt->execute([':name' => $name]);
    $id = $stmt->fetchColumn();
    if ($id) {
        return (int)$id;
    }

    $stmt = $pdo->prepare("INSERT INTO CATEGORIA_TB (NOMBRE, ID_ESTADO) VALUES (:name, 1)");
    $stmt->execute([':name' => $name]);
    return (int)$pdo->lastInsertId();
}

function ensureBrand(PDO $pdo, string $name): int
{
    $stmt = $pdo->prepare("SELECT ID_MARCA FROM MARCA_TB WHERE LOWER(NOMBRE) = LOWER(:name) LIMIT 1");
    $stmt->execute([':name' => $name]);
    $id = $stmt->fetchColumn();
    if ($id) {
        return (int)$id;
    }

    $stmt = $pdo->prepare("INSERT INTO MARCA_TB (NOMBRE, ID_ESTADO) VALUES (:name, 1)");
    $stmt->execute([':name' => $name]);
    return (int)$pdo->lastInsertId();
}

function ensureProduct(PDO $pdo, array $data, int $categoryId): int
{
    $stmt = $pdo->prepare("SELECT ID_PRODUCTO FROM PRODUCTO_TB WHERE NOMBRE = :nombre LIMIT 1");
    $stmt->execute([':nombre' => $data['name']]);
    $id = $stmt->fetchColumn();

    if (!$id) {
        $stmt = $pdo->prepare("INSERT INTO PRODUCTO_TB (NOMBRE, DESCRIPCION, PRECIO, ID_CATEGORIA, ID_MARCA, ID_ESTADO)
            VALUES (:nombre, :descripcion, :precio, :categoria, :marca, 1)");
        $stmt->execute([
            ':nombre' => $data['name'],
            ':descripcion' => $data['description'],
            ':precio' => $data['price'],
            ':categoria' => $categoryId,
            ':marca' => $data['brand_id'],
        ]);
        $id = $pdo->lastInsertId();
    }

    $id = (int)$id;

    $stmt = $pdo->prepare("SELECT 1 FROM PRODUCTO_IMAGE_TB WHERE ID_PRODUCTO = :id AND URL_IMAGE = :url LIMIT 1");
    $stmt->execute([':id' => $id, ':url' => $data['image']]);
    if (!$stmt->fetchColumn()) {
        $stmt = $pdo->prepare("INSERT INTO PRODUCTO_IMAGE_TB (ID_PRODUCTO, URL_IMAGE, ID_ESTADO) VALUES (:id, :url, 1)");
        $stmt->execute([':id' => $id, ':url' => $data['image']]);
    }

    $stmt = $pdo->prepare("SELECT ID_INVENTARIO FROM INVENTARIO_TB WHERE ID_PRODUCTO = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $invId = $stmt->fetchColumn();
    if (!$invId) {
        $stmt = $pdo->prepare("INSERT INTO INVENTARIO_TB (ID_PRODUCTO, STOCK, ID_ESTADO) VALUES (:id, :stock, 1)");
        $stmt->execute([':id' => $id, ':stock' => $data['stock']]);
    }

    return $id;
}

$categoryId = ensureCategory($pdo, 'Componentes');

$brandGenerico = ensureBrand($pdo, 'Generico');
$brandAdata = ensureBrand($pdo, 'ADATA');
$brandHikvision = ensureBrand($pdo, 'HIKVISION');

$productos = [
    [
        'name' => 'CONVERTIDO DE BAHIA OPTICA PARA DISCO DURO HDD-SSD – CADDY',
        'price' => 6000,
        'description' => "Este adaptador de disco duro le permite agregar un segundo disco duro a los sistemas portatiles.\nPuede almacenar y realizar copias de seguridad de datos criticos rapidamente.\nSimplemente retire su unidad de CD/DVD-ROM y coloque el Caddy (con el disco duro anadido) en su lugar. Este es un buen adaptador de trabajo y esta garantizado.\n\nSoporte: SATA\nSoporte de disco duro: 2.5 HDD, SATA, SATAII SSD\nGrosor de la caja: 0.500 in",
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2026/02/KIT007.webp',
        'stock' => 14,
        'brand_id' => $brandGenerico,
    ],
    [
        'name' => 'SSD 2.5 Adata Ultimate SU650 – 512GB – 520MB/s',
        'price' => 41400,
        'description' => "Capacidad: 512GB\nFactor de forma: 2.5\"\nNAND Flash: 3D NAND\nDimensiones (L x An x Al): 100,45 x 69,85 x 7mm / 3,95 x 2,75 x 0,27\"\nPeso: 59.5g / 2.1oz\nInterfaz: SATA 6Gb/s\nLectura secuencial (max*): Hasta 520MB/s\nEscritura secuencial (max*): Hasta 450MB/s\n4 KB de IOPS de lectura aleatoria (max*): Hasta 40K\n4 KB de IOPS de escritura aleatoria (max*): Hasta 75K\nTemperatura de operacion: 0 a 70C\nTemperatura de almacenamiento: -40 a 85C\nResistencia a golpes: 1500G / 0,5ms\nMTBF: 2.000.000 horas",
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2023/11/ASU650SS-512GT-R.webp',
        'stock' => 10,
        'brand_id' => $brandAdata,
    ],
    [
        'name' => 'SSD M.2 1TB HIKVISION HIKSEMI WAVE – 2500 MB/s',
        'price' => 72000,
        'description' => "Especificacion\nTamano del producto: M.2 2280\nCapacidad: 1TB\nMax. Sec. Lectura (MB/s): 2500 MB/s\nMax. Sec. Escritura (MB/s): 1025 MB/s\nMedio de almacenamiento: NAND 3D\nInterfaz: PCIe\nMTBF: 1.500.000 horas\nTemperatura de operacion: 0-70 C\nTemperatura de almacenamiento: -40 C a 85 C\nPeso: <=7g",
        'image' => 'https://loremflickr.com/700/450/technology?lock=905',
        'stock' => 8,
        'brand_id' => $brandHikvision,
    ],
];

$ids = [];
foreach ($productos as $producto) {
    $ids[] = ensureProduct($pdo, $producto, $categoryId);
}

echo "Productos agregados/actualizados: " . implode(', ', $ids) . PHP_EOL;
