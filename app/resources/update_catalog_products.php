<?php

declare(strict_types=1);

$dsn = 'mysql:host=127.0.0.1;dbname=zohan_tech_store;charset=utf8mb4';
$user = 'root';
$pass = 'CGJS2050';

$products = [
    [
        'name' => 'Memoria RAM 16GB DDR4',
        'brand' => 'Kingston',
        'category' => 'Componentes',
        'description' => "MEMORIA RAM KINGSTON DDR4 3200MHz - 16GB. Especificaciones: 16 GB DDR4, 3200 MHz, DDR4 SDRAM, dimensiones 13.3 x 0.4 x 1.9 pulgadas.",
        'price' => 79000.00,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2025/04/KVR56U46BS8-16.jpg',
    ],
    [
        'name' => 'Laptop HP Pavilion Gaming 15-EC1038LA',
        'brand' => 'HP',
        'category' => 'Laptops',
        'description' => "HP Pavilion Gaming 15-EC1038LA con Ryzen 7 4800H, 8GB DDR4, SSD 512GB y GTX 1650Ti 4GB GDDR6. Pantalla 15.6 y Windows 10 en espanol.",
        'price' => 459900.00,
        'image' => 'https://cyberteamcr.com/wp-content/uploads/2024/02/162D6LA-6.webp',
    ],
    [
        'name' => 'Audifonos Pro Razer BlackShark V2 Pro',
        'brand' => 'Razer',
        'category' => 'Perifericos',
        'description' => "Headset gamer Razer BlackShark V2 Pro Rainbow Six Edition. Driver 50 mm TriForce Titanium, 12Hz-28kHz, microfono HyperClear, audio espacial THX y bateria hasta 24h.",
        'price' => 119900.00,
        'image' => 'https://i3.wp.com/i.ibb.co/PFZx7zs/audifono-razer-blackshark-v2-pro.jpg?w=1000&resize=1000,1000&ssl=1',
    ],
    [
        'name' => 'Fuente de Poder 750W MSI MAG A750BN',
        'brand' => 'MSI',
        'category' => 'Fuentes de Poder',
        'description' => "MSI MAG A750BN PCIE5 III 750W 80 Plus Bronze no modular. Incluye conectores ATX/EPS/PCIe, protecciones OVP/OCP/SCP/OPP/UVP y ventilador de 120 mm.",
        'price' => 40900.00,
        'image' => 'https://cyberteamcr.com/wp-content/uploads/2026/03/22681_20661.jpg',
    ],
    [
        'name' => 'Cargador USB-C 60W Samsung',
        'brand' => 'Samsung',
        'category' => 'Accesorios',
        'description' => "Cargador USB-C Samsung de 60W con carga super rapida para dispositivos Galaxy, incluyendo smartphones y Galaxy Book.",
        'price' => 19900.00,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2026/03/EP-T6010NBEGCA.jpg',
    ],
    [
        'name' => 'Mochila/Funda Targus Slipskin 14',
        'brand' => 'Targus',
        'category' => 'Accesorios',
        'description' => "Funda Targus Slipskin para laptop de 14 pulgadas, acolchada, con asas ocultables y bolsillo frontal con cremallera.",
        'price' => 4900.00,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2022/06/TSS932.webp',
    ],
    [
        'name' => 'Soporte Monitor NZXT Small Stand',
        'brand' => 'NZXT',
        'category' => 'Accesorios',
        'description' => "Brazo/soporte de monitor NZXT para paneles hasta 27 pulgadas. Ajustes de altura, giro y pivote para mejor ergonomia.",
        'price' => 15000.00,
        'image' => 'https://extremetechcr.com/wp-content/uploads/2024/11/29004.jpg',
    ],
    [
        'name' => 'Mousepad Logitech G840 XL',
        'brand' => 'Logitech',
        'category' => 'Perifericos',
        'description' => "Mousepad XL 400x900 mm con superficie optimizada para precision gaming, friccion moderada y base de goma antideslizante.",
        'price' => 21000.00,
        'image' => 'https://extremetechcr.com/wp-content/uploads/2024/11/29130.jpg',
    ],
    [
        'name' => 'Cooler Liquido Cooler Master 240L Core ARGB',
        'brand' => 'Cooler Master',
        'category' => 'Refrigeracion',
        'description' => "MasterLiquid 240L Core ARGB con bomba de doble camara Gen S, radiador ampliado, ventiladores ARGB de 120 mm y pasta termica CryoFuze.",
        'price' => 39900.00,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2024/02/MLW-D24M-A18PZ-RW.webp',
    ],
    [
        'name' => 'Laptop Asus VivoBook 15 i5 1235U',
        'brand' => 'Asus',
        'category' => 'Laptops',
        'description' => "ASUS Vivobook 15 con Intel Core i5-1235U, 8GB DDR4, SSD 512GB NVMe, pantalla 15.6 FHD y Windows 11 Pro.",
        'price' => 255000.00,
        'image' => 'https://extremetechcr.com/wp-content/uploads/2024/11/32035.jpg',
    ],
    [
        'name' => 'Laptop Acer Nitro 5 R5 7535HS RTX 3050',
        'brand' => 'Acer',
        'category' => 'Laptops',
        'description' => "Acer Nitro 5 con Ryzen 5 7535HS, 16GB RAM, SSD 512GB, NVIDIA RTX 3050, pantalla 15 QHD 165Hz y Windows 11 Home.",
        'price' => 415900.00,
        'image' => 'https://cyberteamcr.com/wp-content/uploads/2025/05/Diseno-sin-titulo-4.webp',
    ],
    [
        'name' => 'Laptop Lenovo IdeaPad 3 15.6 i3',
        'brand' => 'Lenovo',
        'category' => 'Laptops',
        'description' => "Lenovo IdeaPad 3 15.6 pulgadas con Intel Core i3, 8GB RAM y almacenamiento SSD.",
        'price' => 369950.00,
        'image' => 'https://innovacellcr.com/cdn/shop/files/laptop-lenovo-ideapad-3-15-6-i3-256gb-ssd-4gb-82h801gvus-innovacell-73455.png?v=1761432244&width=540',
    ],
    [
        'name' => 'Laptop HP Pavilion 14 Core i5',
        'brand' => 'HP',
        'category' => 'Laptops',
        'description' => "HP Pavilion 14 con Intel Core i5-1135G7, 8GB DDR4, 512GB SSD + 32GB Optane, pantalla 14 HD y Windows 11.",
        'price' => 325000.00,
        'image' => 'https://img.pacifiko.com/PROD/resize/1/1000x1000/NWRhZTMyOD_479.jpg',
    ],
    [
        'name' => 'Laptop Dell Inspiron 15 Touch i5 1235U',
        'brand' => 'Dell',
        'category' => 'Laptops',
        'description' => "Dell Inspiron 15 con pantalla tactil FHD 15.6, Intel Core i5-1235U, 16GB RAM y SSD PCIe de 512GB.",
        'price' => 336240.00,
        'image' => 'https://img.pacifiko.com/PROD/resize/1/1000x1000/NzlmN2Y2ZG_2.jpg',
    ],
    [
        'name' => 'Laptop MSI Katana 15 i7 RTX 4060',
        'brand' => 'MSI',
        'category' => 'Laptops',
        'description' => "MSI Katana 15 con Intel i7-13620H, 16GB DDR5, SSD 1TB NVMe, RTX 4060 8GB y pantalla 15.6 FHD 144Hz.",
        'price' => 859080.00,
        'image' => 'https://img.pacifiko.com/PROD/resize/1/1000x1000/MTU2YjdjYm.jpg',
    ],
    [
        'name' => 'Laptop Gigabyte G5 KC RTX 3060',
        'brand' => 'Gigabyte',
        'category' => 'Laptops',
        'description' => "Gigabyte G5 KC de 15.6 FHD 144Hz con Intel Core i5-10500H, RTX 3060, 16GB RAM y SSD 512GB.",
        'price' => 573970.00,
        'image' => 'https://via.placeholder.com/1200x800?text=Gigabyte+G5+KC',
    ],
    [
        'name' => 'Laptop Samsung Galaxy Book Pro 360',
        'brand' => 'Samsung',
        'category' => 'Laptops',
        'description' => "Samsung Galaxy Book Pro 360 2-en-1 con i7-1260P, 16GB RAM, SSD 2TB y pantalla tactil AMOLED FHD 15.6.",
        'price' => 815280.00,
        'image' => 'https://img.pacifiko.com/PROD/resize/1/1000x1000/ZGY0MmI3YW_2.jpg',
    ],
    [
        'name' => 'Tarjeta Grafica MSI RTX 4060 Ventus 2X',
        'brand' => 'MSI',
        'category' => 'Tarjetas Graficas',
        'description' => "MSI GeForce RTX 4060 Ventus 2X Black OC con 8GB GDDR6 y core clock de 2505 MHz.",
        'price' => 179000.00,
        'image' => 'https://extremetechcr.com/wp-content/uploads/2024/11/29033.jpg',
    ],
    [
        'name' => 'Tarjeta Grafica Gigabyte RTX 4070 Gaming OC',
        'brand' => 'Gigabyte',
        'category' => 'Tarjetas Graficas',
        'description' => "Gigabyte GeForce RTX 4070 Gaming OC con 12GB GDDR6X y core clock de 2565 MHz.",
        'price' => 425000.00,
        'image' => 'https://extremetechcr.com/wp-content/uploads/2024/11/29721.jpg',
    ],
    [
        'name' => 'Memoria RAM 16GB DDR5 Kingston 5600',
        'brand' => 'Kingston',
        'category' => 'Componentes',
        'description' => "Kingston DDR5 5600MHz 16GB, modulo DDR5 SDRAM, voltaje 1.1V, SKU KVR56U46BS8-16.",
        'price' => 138000.00,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2025/04/KVR56U46BS8-16.webp',
    ],
    [
        'name' => 'Memoria RAM 32GB DDR5 Crucial 4800',
        'brand' => 'Crucial',
        'category' => 'Componentes',
        'description' => "Kit Crucial DDR5 32GB (2x16GB) a 4800MHz, UDIMM sin buffer, latencia CAS 40, voltaje 1.1V.",
        'price' => 250000.00,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2022/02/CT2K16G48C40U5.jpg',
    ],
    [
        'name' => 'SSD NVMe 1TB Kingston NV3',
        'brand' => 'Kingston',
        'category' => 'Almacenamiento',
        'description' => "SSD M.2 NVMe PCIe 4.0 x4 Kingston NV3 de 1TB, lectura hasta 6000 MB/s y escritura hasta 4000 MB/s.",
        'price' => 83000.00,
        'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSFveL7E-1tsTXt8dWAv-VQPE15keqRiR778A&s',
    ],
    [
        'name' => 'SSD NVMe 2TB Kingston NV3',
        'brand' => 'Kingston',
        'category' => 'Almacenamiento',
        'description' => "SSD M.2 Kingston NV3 NVMe PCIe de 2TB con velocidad de lectura hasta 6000 MB/s.",
        'price' => 165000.00,
        'image' => 'https://via.placeholder.com/1200x800?text=Gigabyte+G5+KC',
    ],
    [
        'name' => 'Fuente de Poder Cooler Master G Gold 750 V2',
        'brand' => 'Cooler Master',
        'category' => 'Fuentes de Poder',
        'description' => "Fuente Cooler Master G Gold 750 V2, 80 Plus Gold, ATX 3.1, ventilador HDB silencioso y cableado en relieve.",
        'price' => 35000.00,
        'image' => 'https://extremetechcr.com/wp-content/uploads/2025/07/43253.jpg',
    ],
    [
        'name' => 'Placa Madre MSI B650 Gaming Plus WiFi',
        'brand' => 'MSI',
        'category' => 'Placa Madre',
        'description' => "Motherboard MSI B650 Gaming Plus WiFi ATX DDR5 para AM5, Wi-Fi 6E, Bluetooth 5.3, 2x M.2 Gen4 y LAN 2.5G.",
        'price' => 122600.00,
        'image' => 'https://faithtechnologycr.com/wp-content/uploads/2026/02/15516_9345.jpg',
    ],
    [
        'name' => 'Disipador Liquido Cooler Master 240 Atmos ARGB',
        'brand' => 'Cooler Master',
        'category' => 'Refrigeracion',
        'description' => "MasterLiquid 240 Atmos ARGB con compatibilidad Intel y AMD, radiador de aluminio de 240 mm y dos ventiladores PWM ARGB.",
        'price' => 65000.00,
        'image' => 'https://www.intelec.co.cr/wp-content/uploads/2024/02/MLW-D24M-A18PZ-RW.webp',
    ],
];

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $pdo->beginTransaction();

    $estadoActivoId = (int)($pdo->query("SELECT ID_ESTADO FROM ESTADO_TB ORDER BY ID_ESTADO ASC LIMIT 1")->fetchColumn() ?: 0);
    if ($estadoActivoId <= 0) {
        $pdo->exec("INSERT INTO ESTADO_TB (NOMBRE) VALUES ('Activo')");
        $estadoActivoId = (int)$pdo->lastInsertId();
    }

    $estadoInactivoId = (int)($pdo->query("SELECT ID_ESTADO FROM ESTADO_TB WHERE LOWER(NOMBRE) = 'inactivo' LIMIT 1")->fetchColumn() ?: 0);
    if ($estadoInactivoId <= 0) {
        $stmtEstado = $pdo->prepare("INSERT INTO ESTADO_TB (NOMBRE) VALUES (:nombre)");
        $stmtEstado->execute([':nombre' => 'Inactivo']);
        $estadoInactivoId = (int)$pdo->lastInsertId();
    }

    $pdo->prepare("UPDATE PRODUCTO_IMAGE_TB SET ID_ESTADO = :estado")->execute([':estado' => $estadoInactivoId]);
    $pdo->prepare("UPDATE PRODUCTO_TB SET ID_ESTADO = :estado")->execute([':estado' => $estadoInactivoId]);

    $stmtSelMarca = $pdo->prepare("SELECT ID_MARCA FROM MARCA_TB WHERE NOMBRE = :nombre LIMIT 1");
    $stmtInsMarca = $pdo->prepare("INSERT INTO MARCA_TB (NOMBRE, ID_ESTADO) VALUES (:nombre, :estado)");

    $stmtSelCategoria = $pdo->prepare("SELECT ID_CATEGORIA FROM CATEGORIA_TB WHERE NOMBRE = :nombre LIMIT 1");
    $stmtInsCategoria = $pdo->prepare("INSERT INTO CATEGORIA_TB (NOMBRE, ID_ESTADO) VALUES (:nombre, :estado)");

    $stmtInsProducto = $pdo->prepare(
        "INSERT INTO PRODUCTO_TB (NOMBRE, DESCRIPCION, PRECIO, ID_CATEGORIA, ID_MARCA, ID_ESTADO)
         VALUES (:nombre, :descripcion, :precio, :idCategoria, :idMarca, :estado)"
    );

    $stmtInsImagen = $pdo->prepare(
        "INSERT INTO PRODUCTO_IMAGE_TB (ID_PRODUCTO, URL_IMAGE, ID_ESTADO)
         VALUES (:idProducto, :url, :estado)"
    );

    foreach ($products as $product) {
        $stmtSelMarca->execute([':nombre' => $product['brand']]);
        $idMarca = (int)($stmtSelMarca->fetchColumn() ?: 0);
        if ($idMarca <= 0) {
            $stmtInsMarca->execute([':nombre' => $product['brand'], ':estado' => $estadoActivoId]);
            $idMarca = (int)$pdo->lastInsertId();
        }

        $stmtSelCategoria->execute([':nombre' => $product['category']]);
        $idCategoria = (int)($stmtSelCategoria->fetchColumn() ?: 0);
        if ($idCategoria <= 0) {
            $stmtInsCategoria->execute([':nombre' => $product['category'], ':estado' => $estadoActivoId]);
            $idCategoria = (int)$pdo->lastInsertId();
        }

        $stmtInsProducto->execute([
            ':nombre' => $product['name'],
            ':descripcion' => $product['description'],
            ':precio' => $product['price'],
            ':idCategoria' => $idCategoria,
            ':idMarca' => $idMarca,
            ':estado' => $estadoActivoId,
        ]);

        $idProducto = (int)$pdo->lastInsertId();

        $stmtInsImagen->execute([
            ':idProducto' => $idProducto,
            ':url' => $product['image'],
            ':estado' => $estadoActivoId,
        ]);
    }

    $pdo->commit();

    echo "Catalogo actualizado. Productos insertados: " . count($products) . PHP_EOL;
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "Error: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
