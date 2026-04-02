<?php

class AccountModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $sql = "SELECT
                    C.ID_CUENTA,
                    C.IDENTIFICACION,
                    C.USERNAME,
                    C.INTENTOS_FALLIDOS,
                    C.ULTIMO_LOGIN,
                    C.ID_ESTADO,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO,
                    U.APELLIDO_MATERNO,
                    T.NOMBRE AS TIPO_USUARIO,
                    E.NOMBRE AS ESTADO
                FROM CUENTA_TB C
                JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = C.IDENTIFICACION
                LEFT JOIN TIPO_USUARIO_TB T
                    ON T.ID_TIPO_USUARIO = U.ID_TIPO_USUARIO
                LEFT JOIN ESTADO_TB E
                    ON E.ID_ESTADO = C.ID_ESTADO
                ORDER BY C.FECHA_CREACION DESC, C.ID_CUENTA DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id)
    {
        $sql = "SELECT
                    C.*,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO,
                    U.APELLIDO_MATERNO,
                    T.NOMBRE AS TIPO_USUARIO,
                    E.NOMBRE AS ESTADO
                FROM CUENTA_TB C
                JOIN USUARIO_TB U
                    ON U.IDENTIFICACION = C.IDENTIFICACION
                LEFT JOIN TIPO_USUARIO_TB T
                    ON T.ID_TIPO_USUARIO = U.ID_TIPO_USUARIO
                LEFT JOIN ESTADO_TB E
                    ON E.ID_ESTADO = C.ID_ESTADO
                WHERE C.ID_CUENTA = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function availableUsers(?int $excludeAccountId = null): array
    {
        $sql = "SELECT
                    U.IDENTIFICACION,
                    U.NOMBRE,
                    U.APELLIDO_PATERNO,
                    U.APELLIDO_MATERNO,
                    T.NOMBRE AS TIPO_USUARIO
                FROM USUARIO_TB U
                LEFT JOIN TIPO_USUARIO_TB T
                    ON T.ID_TIPO_USUARIO = U.ID_TIPO_USUARIO
                LEFT JOIN CUENTA_TB C
                    ON C.IDENTIFICACION = U.IDENTIFICACION";

        $params = [];

        if ($excludeAccountId !== null) {
            $sql .= " AND C.ID_CUENTA != :excludeAccountId";
            $params[':excludeAccountId'] = $excludeAccountId;
        }

        $sql .= "
                WHERE U.ID_ESTADO = 1
                  AND (C.ID_CUENTA IS NULL";

        if ($excludeAccountId !== null) {
            $sql .= " OR U.IDENTIFICACION = (
                        SELECT IDENTIFICACION
                        FROM CUENTA_TB
                        WHERE ID_CUENTA = :currentAccountId
                    )";
            $params[':currentAccountId'] = $excludeAccountId;
        }

        $sql .= ")
                ORDER BY U.NOMBRE ASC, U.APELLIDO_PATERNO ASC, U.APELLIDO_MATERNO ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function statuses(): array
    {
        $sql = "SELECT ID_ESTADO, NOMBRE
                FROM ESTADO_TB
                ORDER BY ID_ESTADO ASC";

        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): void
    {
        $sql = "INSERT INTO CUENTA_TB
                    (IDENTIFICACION, USERNAME, PASSWORD, INTENTOS_FALLIDOS, ULTIMO_LOGIN, ID_ESTADO)
                VALUES
                    (:identificacion, :username, :password, 0, NULL, :estado)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':identificacion' => $data['identificacion'],
            ':username' => $data['username'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':estado' => $data['estado']
        ]);
    }

    public function update(int $id, array $data): void
    {
        $params = [
            ':id' => $id,
            ':identificacion' => $data['identificacion'],
            ':username' => $data['username'],
            ':estado' => $data['estado']
        ];

        $sql = "UPDATE CUENTA_TB
                SET IDENTIFICACION = :identificacion,
                    USERNAME = :username,
                    ID_ESTADO = :estado";

        if (!empty($data['password'])) {
            $sql .= ",
                    PASSWORD = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $sql .= "
                WHERE ID_CUENTA = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function delete(int $id): void
    {
        $sql = "UPDATE CUENTA_TB
                SET ID_ESTADO = 2
                WHERE ID_CUENTA = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
