<?php

class Storage_Admin extends Storage_Base
{
    protected $table_name = 'admin';

    public function insert($data)
    {
        if (isset($data['password'])) {
            $data['password'] = hash_password($data['password']);
        }

        return $this->database->insert($this->table_name, $data);
    }
}
