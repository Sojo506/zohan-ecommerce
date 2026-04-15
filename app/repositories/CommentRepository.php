<?php

class CommentRepository
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
                    C.AUTOR_NOMBRE,
                    C.META,
                    COALESCE(C.AYUDA_TOTAL, 0) AS AYUDA_TOTAL,
                    COALESCE(C.COMPRA_VERIFICADA, 0) AS COMPRA_VERIFICADA,
                    C.FECHA_COMENTARIO,
                    E.NOMBRE AS ESTADO,
                    P.NOMBRE AS PRODUCTO,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO,
                    COALESCE(
                        NULLIF(C.AUTOR_NOMBRE, ''),
                        NULLIF(CONCAT_WS(' ', U.NOMBRE, U.APELLIDO_PATERNO), '')
                    ) AS NOMBRE_MOSTRAR
                FROM COMENTARIO_TB C
                JOIN PRODUCTO_TB P
                    ON P.ID_PRODUCTO = C.ID_PRODUCTO
                LEFT JOIN USUARIO_TB U
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

    public function findByProduct(int $productId): array
    {
        $sql = "SELECT
                    C.ID_COMENTARIO,
                    C.ID_PRODUCTO,
                    C.IDENTIFICACION,
                    COALESCE(NULLIF(C.AUTOR_NOMBRE, ''), NULLIF(CONCAT_WS(' ', U.NOMBRE, U.APELLIDO_PATERNO), ''), 'Cliente') AS AUTOR_NOMBRE,
                    COALESCE(C.META, '') AS META,
                    C.CALIFICACION,
                    C.COMENTARIO,
                    C.FECHA_COMENTARIO,
                    COALESCE(C.AYUDA_TOTAL, 0) AS AYUDA_TOTAL,
                    COALESCE(C.COMPRA_VERIFICADA, 0) AS COMPRA_VERIFICADA
                FROM COMENTARIO_TB C
                LEFT JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = C.IDENTIFICACION
                WHERE C.ID_PRODUCTO = :product
                  AND C.ID_ESTADO = 1
                ORDER BY C.FECHA_COMENTARIO DESC, C.ID_COMENTARIO DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product' => $productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function summaryByProduct(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                ROUND(AVG(CALIFICACION), 1) AS RATING_PROMEDIO,
                COUNT(*) AS TOTAL_COMENTARIOS
            FROM COMENTARIO_TB
            WHERE ID_PRODUCTO = :product
              AND ID_ESTADO = 1
        ");

        $stmt->execute([':product' => $productId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'RATING_PROMEDIO' => isset($row['RATING_PROMEDIO']) ? (float)$row['RATING_PROMEDIO'] : 0.0,
            'TOTAL_COMENTARIOS' => isset($row['TOTAL_COMENTARIOS']) ? (int)$row['TOTAL_COMENTARIOS'] : 0,
        ];
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
                (ID_PRODUCTO, IDENTIFICACION, AUTOR_NOMBRE, META, CALIFICACION, COMENTARIO, AYUDA_TOTAL, COMPRA_VERIFICADA, ID_ESTADO)
                VALUES (:product, :ident, :author, :meta, :rating, :comment, 0, 1, 1)";

        $userStmt = $this->db->prepare("
            SELECT CONCAT_WS(' ', NOMBRE, APELLIDO_PATERNO) AS NOMBRE_COMPLETO
            FROM USUARIO_TB
            WHERE IDENTIFICACION = :ident
            LIMIT 1
        ");
        $userStmt->execute([':ident' => $identificacion]);
        $author = (string)($userStmt->fetchColumn() ?: '');

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':product' => $productId,
            ':ident' => $identificacion,
            ':author' => $author,
            ':meta' => '',
            ':rating' => $rating,
            ':comment' => $comment
        ]);
    }
}
