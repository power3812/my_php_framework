<?php

class Storage_Database_MySQL extends Storage_Database
{
    public function select(
        string $table_name,
        array $columns = ['*'],
        array $where = [],
        array $order_by = [],
        ?int $limit = null,
        ?int $offset = null
    ) {
        $sql         = 'SELECT ' . implode(',', $columns) . ' FROM ' . $table_name;
        $bind_params = [];

        if (!is_empty($where)) {
            $conditions = $this->makeConditions($where);

            $sql        .= $conditions['sql'];
            $bind_params = $conditions['bind_params'];
        }

        if (!is_empty($order_by)) {
            $sql .= $this->makeOrderBySql($order_by);
        }

        if ($limit !== null) {
            $sql                  .= ' LIMIT :limit ';
            $bind_params[':limit'] = $limit;
        }

        if ($offset !== null) {
            $sql                   .= ' OFFSET :offset ';
            $bind_params[':offset'] = $offset;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindValues($stmt, $bind_params);
        $stmt->execute();

        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }

    public function count(string $table_name, array $where = [], array $order_by = [], ?int $limit = null, ?int $offset = null)
    {
        $sql         = 'SELECT COUNT(*) AS cnt FROM ' . $table_name;
        $bind_params = [];

        if (!is_empty($where)) {
            $conditions = $this->makeConditions($where);

            $sql        .= $conditions['sql'];
            $bind_params = $conditions['bind_params'];
        }

        if (!is_empty($order_by)) {
            $sql .= $this->makeOrderBySql($order_by);
        }

        if ($limit !== null) {
            $sql                  .= ' LIMIT :limit ';
            $bind_params[':limit'] = $limit;
        }

        if ($offset !== null) {
            $sql                   .= ' OFFSET :offset ';
            $bind_params[':offset'] = $offset;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindValues($stmt, $bind_params);
        $stmt->execute();
        $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $record[0]['cnt'];
    }
}
