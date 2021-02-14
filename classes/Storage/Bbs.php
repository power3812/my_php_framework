<?php

class Storage_Bbs extends Storage_Base
{
    protected $table_name  = 'posts';
    protected $input_rules = [
        'username' => [
            'name'   => 'ユーザー名',
            'length' => [
                'min'  => 3,
                'max'  => 16,
                'unit' => '文字',
            ],
        ],
        'title' => [
            'name'   => 'タイトル',
            'length' => [
                'min'  => 10,
                'max'  => 32,
                'unit' => '文字',
            ],
            'required' => true,
        ],
        'message' => [
            'name'   => 'メッセージ',
            'length' => [
                'min'  => 10,
                'max'  => 200,
                'unit' => '文字',
            ],
            'required' => true,
        ],
        'password' => [
            'name'   => 'パスワード',
            'length' => [
                'number' => 4,
                'unit'   => '桁',
            ],
            'digit' => true,
        ],
        'image' => [
            'name'         => '画像',
            'display_unit' => 'MB',
            'extension' => [
                'jpeg',
                'jpg',
                'png',
                'gif',
            ],
            'file_size' => [
                'max' => 1048576,
            ],
        ],
    ];

    public function getInputRules()
    {
        return $this->input_rules;
    }

    public function insert($data)
    {
        if (isset($data['password'])) {
            $data['password'] = hash_password($data['password']);
        }

        $this->database->insert($this->table_name, $data);
    }

    public function softDelete($id)
    {
        $values               = [];
        $values['is_deleted'] = 1;

        return $this->updateById($id, $values);
    }

    public function bulkSoftDelete($ids)
    {
        $values               = [];
        $values['is_deleted'] = 1;

        return $this->updateByIds($ids, $values);
    }

    public function softRecovery($id)
    {
        $values               = [];
        $values['is_deleted'] = 0;

        return $this->updateById($id, $values);
    }

    public function deleteImage($id)
    {
        return $this->updateById($id, ['image' => null]);
    }
}
