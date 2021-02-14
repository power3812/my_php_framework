<?php

class Storage_User extends Storage_Base
{
    protected $table_name  = 'users';
    protected $input_rules = [
        'username' => [
            'name'   => 'ユーザー名',
            'length' => [
                'min'  => 3,
                'max'  => 16,
                'unit' => '文字',
            ],
            'required' => true,
        ],
        'email' => [
            'name'          => 'メールアドレス',
            'required_word' => '@',
            'required'      => true,
        ],
        'password' => [
            'name'   => 'パスワード',
            'length' => [
                'min'  => 8,
                'max'  => 16,
                'unit' => '文字',
            ],
            'required' => true,
        ],
    ];

    public function getInputRules()
    {
        return $this->input_rules;
    }

    public function softDelete($id)
    {
        $values               = [];
        $values['is_deleted'] = 1;

        return $this->updateById($id, $values);
    }

    public function isEmailExists($email)
    {
        $user = $this->selectOne(
            ['*'],
            [
                [
                    'column' => 'email',
                    'value'  => $email,
                ]
            ]
        );

        return (!is_empty($user)) ? true : false;
    }
}
