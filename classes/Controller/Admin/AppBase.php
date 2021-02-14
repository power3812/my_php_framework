<?php

abstract class Controller_Admin_AppBase extends Controller_AppBase
{
    protected $login_admin = null;
    protected $is_login    = false;

    public function setUp()
    {
        parent::setUp();

        $this->login_admin = $this->getLoginAdmin();
        $this->is_login    = !is_empty($this->login_admin);
    }

    protected function getLoginAdmin()
    {
        $session  = new Session();
        $admin_id = $session->get('admin_id');

        if (is_empty($admin_id)) {
            return null;
        }

        $storage_admin = new Storage_Admin();

        $admin = $storage_admin->selectOne(
            ['*'],
            [
                [
                    'column' => 'id',
                    'value'  => $admin_id,
                ],
                [
                    'column' => 'is_deleted',
                    'value'  => 0,
                ],
            ]
        );

        return is_empty($admin) ? null : $admin;
    }
}
