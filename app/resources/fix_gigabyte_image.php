<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=zohan_tech_store;charset=utf8mb4','root','CGJS2050',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$sql = "UPDATE PRODUCTO_IMAGE_TB i
        JOIN PRODUCTO_TB p ON p.ID_PRODUCTO = i.ID_PRODUCTO
        SET i.URL_IMAGE = 'https://via.placeholder.com/1200x800?text=Gigabyte+G5+KC'
        WHERE p.NOMBRE = 'Laptop Gigabyte G5 KC RTX 3060'
          AND p.ID_ESTADO = 1
          AND i.ID_ESTADO = 1";
$rows = $pdo->exec($sql);
echo "Imagen Gigabyte actualizada: {$rows}\n";