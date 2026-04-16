<?php
require_once __DIR__ . '/../autoload.php';

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

$categoryId = ensureCategory($pdo, 'Accesorios');

$brandApple = ensureBrand($pdo, 'Apple');
$brandBeats = ensureBrand($pdo, 'Beats');
$brandSkullcandy = ensureBrand($pdo, 'Skullcandy');

$productos = [
    [
        'name' => 'APPLE AIRPODS MAX USB-C',
        'price' => 329900,
        'description' => "La mejor experiencia de audio sobre la oreja: el controlador dinamico disenado por Apple proporciona audio de alta fidelidad. El audio computacional combina un diseno acustico personalizado con el chip Apple H1 y el software para experiencias auditivas innovadoras.\nCinco colores frescos: Midnight, Starlight, azul, morado y naranja. Incluye funda inteligente a juego.\nCancelacion activa de ruido de nivel profesional para sumergirte en la musica.\nModo Transparencia para escuchar e interactuar con el entorno.\nAudio espacial personalizado con seguimiento dinamico de la cabeza y Dolby Atmos.\nDiseno acustico con diadema de malla y almohadillas de espuma viscoelastica para un ajuste excepcional.\nExperiencia magica: empareja con solo acercarlos, pausa al quitartelos y cambio automatico entre dispositivos.",
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2024/12/%E2%80%8EMWW43AMA.webp',
        'stock' => 6,
        'brand_id' => $brandApple,
    ],
    [
        'name' => 'BEATS POWERBEATS PRO 2',
        'price' => 159900,
        'description' => "Ajuste flexible con ganchos ajustables para un ajuste comodo y seguro. Hasta 30 grados de rotacion y extension de 4 mm.\nCancelacion de ruido potente para entrenar con enfoque.\nTecnologia BassUp con controladores dinamicos de 11 mm para graves intensos.\nDiseno a prueba de todo con tecnologia SweatGuard que protege contra agua, sudor y polvo.",
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2025/03/MX753LLA.jpg',
        'stock' => 8,
        'brand_id' => $brandBeats,
    ],
    [
        'name' => 'Audifonos Skullcandy Crusher ANC 2 Bluetooth – BONE',
        'price' => 121500,
        'description' => "Graves inmejorables con Crusher Sensory Bass y cancelacion activa de ruido.\nBateria de hasta 50 horas + carga rapida.\nCancelacion activa de ruido ajustable con 4 microfonos.\nTecnologia Skull-iQ con funciones inteligentes.\nSonido personal de Mimi, emparejamiento multipunto y control por voz.\nBluetooth v5.2, diseno plegable y controles de llamadas y volumen.",
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2024/09/S6CAW-S951.webp',
        'stock' => 7,
        'brand_id' => $brandSkullcandy,
    ],
];

$ids = [];
foreach ($productos as $producto) {
    $ids[] = ensureProduct($pdo, $producto, $categoryId);
}

echo "Productos accesorios agregados/actualizados: " . implode(', ', $ids) . PHP_EOL;
