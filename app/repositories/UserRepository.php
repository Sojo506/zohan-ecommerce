<?php

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }
    
    public function all()
    {
        $currentUser = $_SESSION['user']['identificacion'];

        $sql = "SELECT 
                U.IDENTIFICACION,
                U.NOMBRE,
                U.APELLIDO_PATERNO,
                U.APELLIDO_MATERNO,
                T.NOMBRE AS TIPO,
                E.NOMBRE AS ESTADO
            FROM USUARIO_TB U
            JOIN TIPO_USUARIO_TB T 
                ON T.ID_TIPO_USUARIO = U.ID_TIPO_USUARIO
            JOIN ESTADO_TB E 
                ON E.ID_ESTADO = U.ID_ESTADO
            WHERE U.IDENTIFICACION != :currentUser
            ORDER BY U.FECHA_REGISTRO DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':currentUser' => $currentUser
        ]);

        return $stmt->fetchAll();
    }
    /*
    public function all()
    {
        $sql = "SELECT 
                    U.IDENTIFICACION,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO,
                    U.APELLIDO_MATERNO,
                    T.NOMBRE AS TIPO,
                    E.NOMBRE AS ESTADO
                FROM USUARIO_TB U
                JOIN TIPO_USUARIO_TB T 
                    ON T.ID_TIPO_USUARIO = U.ID_TIPO_USUARIO
                JOIN ESTADO_TB E 
                    ON E.ID_ESTADO = U.ID_ESTADO
                ORDER BY U.FECHA_REGISTRO DESC";

        return $this->db->query($sql)->fetchAll();
    }*/

    public function find($id)
    {
        $sql = "SELECT 
                    U.*,
                    T.NOMBRE AS TIPO
                FROM USUARIO_TB U
                JOIN TIPO_USUARIO_TB T
                    ON T.ID_TIPO_USUARIO = U.ID_TIPO_USUARIO
                WHERE U.IDENTIFICACION = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function emails($id)
    {
        $sql = "SELECT CORREO
                FROM CORREO_TB
                WHERE IDENTIFICACION = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetchAll();
    }

    public function phones($id)
    {
        $sql = "SELECT TELEFONO
                FROM TELEFONO_TB
                WHERE IDENTIFICACION = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetchAll();
    }

    public function changeStatus($id, $status)
    {
        $sql = "UPDATE USUARIO_TB
                SET ID_ESTADO = :status
                WHERE IDENTIFICACION = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    public function changeRole($id, $role)
    {
        $sql = "UPDATE USUARIO_TB
                SET ID_TIPO_USUARIO = :role
                WHERE IDENTIFICACION = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':role' => $role,
            ':id' => $id
        ]);
    }
}
