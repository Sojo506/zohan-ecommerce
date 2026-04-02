<?php

class CouponModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all()
    {
        return $this->db->query("
            SELECT *
            FROM CUPON_DESCUENTO_TB
            WHERE ID_ESTADO = 1
            ORDER BY ID_CUPON DESC
        ")->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM CUPON_DESCUENTO_TB
            WHERE ID_CUPON = :id
        ");

        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO CUPON_DESCUENTO_TB
                (CODIGO,PORCENTAJE,FECHA_INICIO,FECHA_FIN,USO_MAXIMO,ID_ESTADO)
                VALUES(:code,:percent,:start,:end,:limit,1)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':code' => $data['code'],
            ':percent' => $data['percent'],
            ':start' => $data['start'],
            ':end' => $data['end'],
            ':limit' => $data['limit']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE CUPON_DESCUENTO_TB
                SET
                CODIGO=:code,
                PORCENTAJE=:percent,
                FECHA_INICIO=:start,
                FECHA_FIN=:end,
                USO_MAXIMO=:limit
                WHERE ID_CUPON=:id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':code' => $data['code'],
            ':percent' => $data['percent'],
            ':start' => $data['start'],
            ':end' => $data['end'],
            ':limit' => $data['limit'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE CUPON_DESCUENTO_TB
            SET ID_ESTADO = 2
            WHERE ID_CUPON = :id
        ");

        $stmt->execute([':id' => $id]);
    }

    public function findValidByCode(string $code): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM CUPON_DESCUENTO_TB
            WHERE CODIGO = :code
              AND ID_ESTADO = 1
              AND FECHA_INICIO <= CURDATE()
              AND FECHA_FIN >= CURDATE()
              AND USO_MAXIMO > 0
            LIMIT 1
        ");

        $stmt->execute([':code' => strtoupper(trim($code))]);
        $coupon = $stmt->fetch();

        return $coupon ?: null;
    }

    public function decrementUsage(int $id): void
    {
        $stmt = $this->db->prepare("
            UPDATE CUPON_DESCUENTO_TB
            SET USO_MAXIMO = USO_MAXIMO - 1
            WHERE ID_CUPON = :id AND USO_MAXIMO > 0
        ");

        $stmt->execute([':id' => $id]);
    }
}
