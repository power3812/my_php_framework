<?php

abstract class Storage_Database
{
    protected $pdo;

    protected $config = [
        'host'     => '127.0.0.1',
        'port'     => '3307',
        'dbname'   => '',
        'username' => '',
        'password' => '',
    ];

    public function __construct(
        string $db_name     = DB_NAME,
        string $db_host     = DB_HOST,
        string $db_username = DB_USERNAME,
        string $db_password = DB_PASSWORD
    ) {
        $dsn = 'mysql:dbname=' . $db_name . ';' . 'host=' . $db_host;

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdo = new PDO($dsn, $db_username, $db_password, $options);
    }

    public function update(string $table_name, array $values, array $where)
    {
        $update_set_values = [];
        $bind_params       = [];

        foreach ($values as $column_name => $value) {
            $update_set_values[]           = $column_name . ' = ' . ':update_' . $column_name;
            $bind_param_name               = ':update_' . $column_name;
            $bind_params[$bind_param_name] = $value;
        }

        $conditions = $this->makeConditions($where);

        $sql  = 'UPDATE ' . $table_name . ' SET ' . implode(',', $update_set_values) . $conditions['sql'];
        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindValues($stmt, array_merge($bind_params, $conditions['bind_params']));

        return $stmt->execute();
    }

    public function insert(string $table_name, array $values)
    {
        $insert_value_names = [];
        $bind_params        = [];

        foreach ($values as $column_name => $value) {
            $bind_param_name               = ':insert_' . $column_name;
            $insert_value_names[]          = $bind_param_name;
            $bind_params[$bind_param_name] = $value;
        }

        $sql  = 'INSERT INTO ' . $table_name . ' (' . implode(',', array_keys($values)) . ') VALUES (' . implode(',', $insert_value_names) . ')';
        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindValues($stmt, $bind_params);

        return $stmt->execute();
    }

    public function delete(string $table_name, array $where)
    {
        $conditions = $this->makeConditions($where);

        $sql  = 'DELETE FROM ' . $table_name . $conditions['sql'];
        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindValues($stmt, $conditions['bind_params']);

        return $stmt->execute();
    }

    protected function makeConditions(array $where)
    {
        if (is_empty($where)) {
            return '';
        }

        $conditions  = [];
        $bind_params = [];

        foreach ($where as $condition) {
            if (!isset($condition['column'])) {
                throw new LogicException('$whereにはcolumnがセットされていなければなりません。');
            }

            if (!isset($condition['operator'])) {
                $condition['operator'] = '=';
            }

            $condition['operator'] = strtoupper($condition['operator']);

            if ($condition['operator'] === 'IN') {
                $bind_param_names = [];

                foreach ($condition['value'] as $index => $value) {
                    $bind_param_name               = ':where_' . $index . '_' . $condition['column'];
                    $bind_param_names[]            = $bind_param_name;
                    $bind_params[$bind_param_name] = $value;
                }

                $conditions[] .= ' ' . $condition['column'] . ' IN (' . implode(', ', $bind_param_names) . ') ';
            } elseif ($condition['operator'] === 'IS_NULL') {
                $conditions[] .= ' ' . $condition['column'] . ' IS NULL ';
            } elseif ($condition['operator'] === 'IS_NOT_NULL') {
                $conditions[] .= ' ' . $condition['column'] . ' IS NOT NULL ';
            } else {
                $conditions[]                 .= ' ' . $condition['column'] . ' ' . $condition['operator'] . ' ' . ':where_' . $condition['column'];
                $bind_param_name               = ':where_' . $condition['column'];
                $bind_params[$bind_param_name] = $condition['value'];
            }
        }

        return [
            'sql'         => ' WHERE ' .  implode(' AND ', $conditions),
            'bind_params' => $bind_params,
        ];
    }

    protected function makeOrderBySql(array $order_by)
    {
        $orders = [];

        foreach ($order_by as $column => $sort_type) {
            $orders[] = $column . ' ' . $sort_type;
        }

        return ' ORDER BY ' . implode(',', $orders);
    }

    protected function bindValues(object $stmt, array $bind_params)
    {
        foreach ($bind_params as $bind_param_name => $bind_param) {
            $stmt->bindValue($bind_param_name, $bind_param, $this->getValueType($bind_param));
        }

        return $stmt;
    }

    protected function getValueType($value)
    {
        if (is_array($value)) {
            throw new LogicException('$valueは配列ではいけません。');
        }

        if (is_int($value) || is_numeric($value)) {
            return PDO::PARAM_INT;
        }

        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }

        return PDO::PARAM_STR;
    }
}
