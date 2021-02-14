<?php

abstract class Storage_Base
{
    protected $database    = null;
    protected $table_name  = '';
    protected $primary_key = 'id';

    public function __construct()
    {
        $this->database = new Storage_Database_MySQL();
    }

    public function select(array $columns = ['*'], array $where = [], array $order_by = [], ?int $limit = null, ?int $offset = null)
    {
        return $this->database->select($this->table_name, $columns, $where, $order_by, $limit, $offset);
    }

    public function selectOne(array $columns = ['*'], array $where = [], array $order_by = [], ?int $offset = null)
    {
        $records = $this->database->select($this->table_name, $columns, $where, $order_by, 1, $offset);

        if (is_empty($records)) {
            return null;
        }

        return $records[0];
    }

    public function selectById($id, array $columns = ['*'])
    {
        return $this->selectOne(
            $columns,
            [
                [
                    'column'   => $this->primary_key,
                    'operator' => '=',
                    'value'    => $id,
                ],
            ]
        );
    }

    public function selectByIds($ids, array $columns = ['*'])
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        return $this->select(
            $columns,
            [
                [
                    'column'   => $this->primary_key,
                    'operator' => 'IN',
                    'value'    => $ids,
                ],
            ]
        );
    }

    public function count(array $where = [], array $order_by = [], ?int $limit = null, ?int $offset = null)
    {
        return $this->database->count($this->table_name, $where, $order_by, $limit, $offset);
    }

    public function update(array $values, array $where)
    {
        return $this->database->update($this->table_name, $values, $where);
    }

    public function updateById($id, array $values)
    {
        return $this->update(
            $values,
            [
                [
                    'column' => $this->primary_key,
                    'value'  => $id,
                ],
            ]
        );
    }

    public function updateByIds($ids, array $values)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        return $this->update(
            $values,
            [
                [
                    'column'   => $this->primary_key,
                    'operator' => 'IN',
                    'value'    => $ids,
                ],
            ]
        );
    }

    public function insert(array $values)
    {
        return $this->database->insert($this->table_name, $values);
    }

    public function delete(array $where)
    {
        return $this->database->delete($this->table_name, $where);
    }

    public function deleteById($id)
    {
        return $this->delete(
            [
                [
                    'column'   => $this->primary_key,
                    'operator' => '=',
                    'value'    => $id,
                ],
            ]
        );
    }

    public function deleteByIds($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        return $this->delete(
            [
                [
                    'column'   => $this->primary_key,
                    'operator' => 'IN',
                    'value'    => $ids,
                ],
            ]
        );
    }
}
