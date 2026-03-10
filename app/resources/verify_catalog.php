<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=zohan_tech_store;charset=utf8mb4','root','CGJS2050',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$count = (int)$pdo->query("SELECT COUNT(*) FROM PRODUCTO_TB WHERE ID_ESTADO = 1")->fetchColumn();
echo "Activos: $count\n";
$sql = "SELECT p.NOMBRE, p.PRECIO, LEFT(p.DESCRIPCION,60) AS DESC_CORTA, i.URL_IMAGE
        FROM PRODUCTO_TB p
        LEFT JOIN PRODUCTO_IMAGE_TB i ON i.ID_PRODUCTO = p.ID_PRODUCTO AND i.ID_ESTADO = 1
        WHERE p.ID_ESTADO = 1
        ORDER BY p.ID_PRODUCTO DESC
        LIMIT 5";
foreach ($pdo->query($sql) as $r) {
    echo "- {$r['NOMBRE']} | {$r['PRECIO']} | {$r['DESC_CORTA']} | {$r['URL_IMAGE']}\n";
}
