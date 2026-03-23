<?php

class BrandRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT *
                FROM MARCA_TB
                WHERE ID_ESTADO = 1
                ORDER BY ID_MARCA DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM MARCA_TB
            WHERE ID_MARCA = :id
        ");

        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function create($name)
    {
        $stmt = $this->db->prepare("
            INSERT INTO MARCA_TB
            (NOMBRE,ID_ESTADO)
            VALUES(:name,1)
        ");

        $stmt->execute([
            ':name' => $name
        ]);
    }

    public function update($id, $name)
    {
        $stmt = $this->db->prepare("
            UPDATE MARCA_TB
            SET NOMBRE = :name
            WHERE ID_MARCA = :id
        ");

        $stmt->execute([
            ':name' => $name,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE MARCA_TB
            SET ID_ESTADO = 2
            WHERE ID_MARCA = :id
        ");

        $stmt->execute([
            ':id' => $id
        ]);
    }
}
