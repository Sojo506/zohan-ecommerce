<?php

class ProductRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function getCategories()
    {
        $sql = "SELECT ID_CATEGORIA, NOMBRE
                FROM CATEGORIA_TB
                WHERE ID_ESTADO = 1
                ORDER BY NOMBRE";

        return $this->db->query($sql)->fetchAll();
    }

    public function getBrands()
    {
        $sql = "SELECT ID_MARCA, NOMBRE
                FROM MARCA_TB
                WHERE ID_ESTADO = 1
                ORDER BY NOMBRE";

        return $this->db->query($sql)->fetchAll();
    }

    public function all()
    {
        $sql = "SELECT 
                p.ID_PRODUCTO,
                p.SKU,
                p.NOMBRE,
                p.PRECIO,
                c.NOMBRE AS CATEGORIA,
                m.NOMBRE AS MARCA,
                GROUP_CONCAT(CONCAT(pi.ID_IMAGEN, '::', pi.URL_IMAGE) SEPARATOR '||') AS IMAGENES
                FROM PRODUCTO_TB p
                JOIN CATEGORIA_TB c ON c.ID_CATEGORIA = p.ID_CATEGORIA
                JOIN MARCA_TB m ON m.ID_MARCA = p.ID_MARCA
                LEFT JOIN PRODUCTO_IMAGE_TB pi
                    ON pi.ID_PRODUCTO = p.ID_PRODUCTO
                    AND pi.ID_ESTADO = 1
                WHERE p.ID_ESTADO = 1
                GROUP BY p.ID_PRODUCTO, p.SKU, p.NOMBRE, p.PRECIO, c.NOMBRE, m.NOMBRE
                ORDER BY p.ID_PRODUCTO DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function create($data)
    {
        $this->db->beginTransaction();

        try {

            $sql = "INSERT INTO PRODUCTO_TB
            (SKU,NOMBRE,DESCRIPCION,PRECIO,STOCK_MINIMO,ID_CATEGORIA,ID_MARCA,ID_ESTADO)
            VALUES
            (:sku,:nombre,:desc,:precio,:stock_min,:categoria,:marca,1)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':sku' => $data['sku'],
                ':nombre' => $data['nombre'],
                ':desc' => $data['descripcion'],
                ':precio' => $data['precio'],
                ':stock_min' => $data['stock_min'],
                ':categoria' => $data['categoria'],
                ':marca' => $data['marca']
            ]);

            $productId = $this->db->lastInsertId();

            // Crear inventario automáticamente
            $sqlInventory = "INSERT INTO INVENTARIO_TB
            (ID_PRODUCTO, STOCK, ID_ESTADO)
            VALUES
            (:producto, 0, 1)";

            $stmtInv = $this->db->prepare($sqlInventory);

            $stmtInv->execute([
                ':producto' => $productId
            ]);

            $this->db->commit();

            return $productId;
        } catch (Exception $e) {

            $this->db->rollBack();
            throw $e;
        }
    }

    public function find($id)
    {
        $sql = "SELECT * FROM PRODUCTO_TB
            WHERE ID_PRODUCTO = :id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE PRODUCTO_TB SET
            SKU = :sku,
            NOMBRE = :nombre,
            DESCRIPCION = :desc,
            PRECIO = :precio,
            STOCK_MINIMO = :stock_min,
            ID_CATEGORIA = :categoria,
            ID_MARCA = :marca
            WHERE ID_PRODUCTO = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':sku' => $data['sku'],
            ':nombre' => $data['nombre'],
            ':desc' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':stock_min' => $data['stock_min'],
            ':categoria' => $data['categoria'],
            ':marca' => $data['marca'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $sql = "UPDATE PRODUCTO_TB
            SET ID_ESTADO = 2
            WHERE ID_PRODUCTO = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    public function addImage($productId, $url)
    {
        $sql = "INSERT INTO PRODUCTO_IMAGE_TB
            (ID_PRODUCTO,URL_IMAGE,ID_ESTADO)
            VALUES(:product,:url,1)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':product' => $productId,
            ':url' => $url
        ]);
    }

    public function getImages($productId)
    {
        $sql = "SELECT * FROM PRODUCTO_IMAGE_TB
            WHERE ID_PRODUCTO = :id
            AND ID_ESTADO = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $productId]);

        return $stmt->fetchAll();
    }

    public function deleteImage($imageId)
    {
        $sql = "UPDATE PRODUCTO_IMAGE_TB
            SET ID_ESTADO = 2
            WHERE ID_IMAGEN = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $imageId]);
    }
}
