<?php

abstract class Controller_User_AppBase extends Controller_AppBase
{
    protected $login_user = null;
    protected $is_login   = false;

    public function setUp()
    {
        parent::setUp();

        $this->login_user = $this->getLoginUser();
        $this->is_login   = !is_empty($this->login_user);
    }

    protected function getLoginUser()
    {
        $session = new Session();
        $user_id = $session->get('user_id');

        if (is_empty($user_id)) {
            return null;
        }

        $storage_user = new Storage_User();

        $user = $storage_user->selectOne(
            ['*'],
            [
                [
                    'column' => 'id',
                    'value'  => $user_id,
                ],
                [
                    'column' => 'is_deleted',
                    'value'  => 0,
                ],
            ]
        );

        return is_empty($user) ? null : $user;
    }
}
