<?php

class CommentModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT
                    C.ID_COMENTARIO,
                    C.CALIFICACION,
                    C.COMENTARIO,
                    C.FECHA_COMENTARIO,
                    E.NOMBRE AS ESTADO,
                    P.NOMBRE AS PRODUCTO,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO
                FROM COMENTARIO_TB C
                JOIN PRODUCTO_TB P
                    ON P.ID_PRODUCTO = C.ID_PRODUCTO
                JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = C.IDENTIFICACION
                JOIN ESTADO_TB E
                    ON E.ID_ESTADO = C.ID_ESTADO
                ORDER BY C.FECHA_COMENTARIO DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM COMENTARIO_TB
            WHERE ID_COMENTARIO = :id
        ");

        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function findVisibleByProduct(int $productId): array
    {
        $sql = "SELECT
                    C.ID_COMENTARIO,
                    C.CALIFICACION,
                    C.COMENTARIO,
                    C.FECHA_COMENTARIO,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO
                FROM COMENTARIO_TB C
                JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = C.IDENTIFICACION
                WHERE C.ID_PRODUCTO = :productId
                  AND C.ID_ESTADO = 1
                ORDER BY C.FECHA_COMENTARIO DESC, C.ID_COMENTARIO DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':productId' => $productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE COMENTARIO_TB
            SET ID_ESTADO = :status
            WHERE ID_COMENTARIO = :id
        ");

        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE COMENTARIO_TB
            SET ID_ESTADO = 2
            WHERE ID_COMENTARIO = :id
        ");

        $stmt->execute([':id' => $id]);
    }

    public function createForProduct(int $productId, string $identificacion, string $comment, int $rating = 5): bool
    {
        $sql = "INSERT INTO COMENTARIO_TB
                (ID_PRODUCTO, IDENTIFICACION, CALIFICACION, COMENTARIO, ID_ESTADO)
                VALUES (:product, :ident, :rating, :comment, 1)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':product' => $productId,
            ':ident' => $identificacion,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
    }
}
