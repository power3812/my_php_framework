<?php

class Controller_Admin_Login extends Controller_Admin_AppBase
{
    public function login()
    {
        if ($this->is_login) {
            $this->redirect('admin/index.php');
        }

        $login_id = $this->getParam('login_id');
        $password = $this->getParam('password');

        $errors = [];

        if ($this->method === 'POST') {
            if (is_empty($login_id)) {
                $errors[] = '管理IDを入力して下さい。';
            }

            if (is_empty($password)) {
                $errors[] = 'パスワードを入力して下さい。';
            }

            if (is_empty($errors)) {
                $storage_admin = new Storage_Admin();

                $admin = $storage_admin->selectOne(
                    ['*'],
                    [
                        [
                            'column' => 'login_id',
                            'value'  => $login_id,
                        ],
                        [
                            'column' => 'is_deleted',
                            'value'  => 0
                        ],
                    ],
                );

                if (!is_empty($admin) && password_verify($password, $admin['password'])) {
                    $session = new Session();
                    $session->set('admin_id', $admin['id']);

                    $this->redirect('admin/index.php');
                } else {
                    $errors[] = '管理者IDとパスワードが一致しません。';
                }
            }
        }

        $this->render('admin/login/login.php', get_defined_vars());
    }

    public function logout()
    {
        $session = new Session();
        $session->delete('admin_id');

        $this->redirect('admin/login.php');
    }
}
