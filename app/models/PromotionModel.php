<?php

class PromotionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT *
                FROM PROMOCION_TB
                WHERE ID_ESTADO = 1
                ORDER BY FECHA_INICIO DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM PROMOCION_TB
            WHERE ID_PROMOCION = :id
        ");

        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO PROMOCION_TB
            (NOMBRE,DESCRIPCION,PORCENTAJE,FECHA_INICIO,FECHA_FIN,ID_ESTADO)
            VALUES(:name,:desc,:percent,:start,:end,1)
        ");

        $stmt->execute([
            ':name' => $data['name'],
            ':desc' => $data['description'],
            ':percent' => $data['percent'],
            ':start' => $data['start'],
            ':end' => $data['end']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE PROMOCION_TB
            SET
                NOMBRE=:name,
                DESCRIPCION=:desc,
                PORCENTAJE=:percent,
                FECHA_INICIO=:start,
                FECHA_FIN=:end
            WHERE ID_PROMOCION=:id
        ");

        $stmt->execute([
            ':name' => $data['name'],
            ':desc' => $data['description'],
            ':percent' => $data['percent'],
            ':start' => $data['start'],
            ':end' => $data['end'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE PROMOCION_TB
            SET ID_ESTADO = 2
            WHERE ID_PROMOCION = :id
        ");

        $stmt->execute([':id' => $id]);
    }

    public function assignProduct($promotionId, $productId)
    {
        // intentar reactivar
        $sql = "UPDATE PROMOCION_PRODUCTO_TB
            SET ID_ESTADO = 1
            WHERE ID_PROMOCION = :promo
            AND ID_PRODUCTO = :product";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':promo' => $promotionId,
            ':product' => $productId
        ]);

        // si no existía fila, insertar
        if ($stmt->rowCount() === 0) {

            $sqlInsert = "INSERT INTO PROMOCION_PRODUCTO_TB
            (ID_PROMOCION, ID_PRODUCTO, ID_ESTADO)
            VALUES (:promo, :product, 1)";

            $stmtInsert = $this->db->prepare($sqlInsert);

            $stmtInsert->execute([
                ':promo' => $promotionId,
                ':product' => $productId
            ]);
        }
    }

    public function products($promoId)
    {
        $stmt = $this->db->prepare("
            SELECT P.ID_PRODUCTO,P.NOMBRE
            FROM PROMOCION_PRODUCTO_TB PP
            JOIN PRODUCTO_TB P
            ON P.ID_PRODUCTO = PP.ID_PRODUCTO
            WHERE PP.ID_PROMOCION = :id
            AND PP.ID_ESTADO = 1
        ");

        $stmt->execute([':id' => $promoId]);

        return $stmt->fetchAll();
    }

    public function removeProduct($promoId, $productId)
    {
        $stmt = $this->db->prepare("
        UPDATE PROMOCION_PRODUCTO_TB
        SET ID_ESTADO = 2
        WHERE ID_PROMOCION = :promo
        AND ID_PRODUCTO = :product
    ");

        $stmt->execute([
            ':promo' => $promoId,
            ':product' => $productId
        ]);
    }
}
