<?php

class CategoryRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        $sql = "SELECT * FROM CATEGORIA_TB
WHERE ID_ESTADO = 1
ORDER BY ID_CATEGORIA DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM CATEGORIA_TB
WHERE ID_CATEGORIA = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function create($name)
    {
        $sql = "INSERT INTO CATEGORIA_TB
(NOMBRE,ID_ESTADO)
VALUES(:name,1)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name]);
    }

    public function update($id, $name)
    {
        $sql = "UPDATE CATEGORIA_TB
SET NOMBRE=:name
WHERE ID_CATEGORIA=:id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $sql = "UPDATE CATEGORIA_TB
SET ID_ESTADO = 2
WHERE ID_CATEGORIA = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
