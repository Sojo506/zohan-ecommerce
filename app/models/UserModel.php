<?php

class UserModel {

    private $db;

    public function __construct(){
        $this->db = Database::connection();
    }

    public function obtenerUsuario($identificacion)
    {
        $pdo = Database::connection();

        $sql = "SELECT 
                u.IDENTIFICACION,
                u.NOMBRE,
                u.APELLIDO_PATERNO,
                u.APELLIDO_MATERNO,
                u.FECHA_REGISTRO,
                c.CORREO
            FROM USUARIO_TB u
            LEFT JOIN CORREO_TB c 
                ON c.IDENTIFICACION = u.IDENTIFICACION
            WHERE u.IDENTIFICACION = :identificacion
            LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'identificacion' => $identificacion
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerStatsUsuario($identificacion)
    {
        $pdo = Database::connection();

        $sql = "SELECT 
                COUNT(DISTINCT v.ID_VENTA) AS total_compras,
                SUM(vp.CANTIDAD) AS productos_comprados,
                SUM(vp.CANTIDAD * vp.PRECIO) AS dinero_gastado
            FROM VENTA_TB v
            JOIN CUENTA_TB c ON c.ID_CUENTA = v.ID_CUENTA
            JOIN VENTA_PRODUCTO_TB vp ON vp.ID_VENTA = v.ID_VENTA
            WHERE c.IDENTIFICACION = :identificacion";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['identificacion' => $identificacion]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================
       API COMPATIBLE CON CONTROLADORES
    ========================= */

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

    public function changeStatus($id, $status): void
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

    public function changeRole($id, $role): void
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
?>
