<?php

class AuditRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT 
                    A.ID_AUDITORIA,
                    A.ACCION,
                    A.TABLA_AFECTADA,
                    A.FECHA,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO
                FROM AUDITORIA_TB A
                LEFT JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = A.IDENTIFICACION
                WHERE A.ID_ESTADO = 1
                ORDER BY A.FECHA DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function log($action, $table)
    {
        if (!isset($_SESSION['user'])) return;

        $sql = "INSERT INTO AUDITORIA_TB
                (ACCION,TABLA_AFECTADA,IDENTIFICACION,ID_ESTADO)
                VALUES(:action,:table,:user,1)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':action' => $action,
            ':table' => $table,
            ':user' => $_SESSION['user']['identificacion']
        ]);
    }
}
