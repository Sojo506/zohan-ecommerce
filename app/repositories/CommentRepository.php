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
}
