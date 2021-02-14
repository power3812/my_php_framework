<?php

class Storage_PreUser extends Storage_Base
{
    protected $table_name = 'pre_users';

    public function insert($data)
    {
        if (isset($data['password'])) {
            $data['password'] = hash_password($data['password']);
        }

        return $this->database->insert($this->table_name, $data);
    }
}
