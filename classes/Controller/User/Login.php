<?php

class Controller_User_Login extends Controller_User_AppBase
{
    protected $token_expires = 60 * 60 * 24;

    public function login()
    {
        if ($this->is_login) {
            $this->redirect('index.php');
        }

        $email    = $this->getParam('email');
        $password = $this->getParam('password');

        $data = [
            'email'    => $email,
            'password' => $password,
        ];

        $errors = [];

        if ($this->method === 'POST') {
            if (is_empty($email)) {
                $errors[] = 'メールアドレスを入力して下さい。';
            }

            if (is_empty($password)) {
                $errors[] = 'パスワードを入力して下さい。';
            }

            if (is_empty($errors)) {
                $storage_user = new Storage_User();

                $where = [
                    [
                        'column' => 'email',
                        'value'  => $email,
                    ],
                    [
                        'column' => 'is_deleted',
                        'value'  => 0,
                    ],
                ];

                $user = $storage_user->selectOne(['*'], $where);

                if (!is_empty($user) && password_verify($password, $user['password'])) {
                    $session = new Session();
                    $session->set('user_id', $user['id']);

                    $this->redirect('index.php');
                } else {
                    $errors[] = 'メールアドレスとパスワードが一致しません。';
                }
            }
        }

        $this->render('user/login/login.php', get_defined_vars());
    }

    public function logout()
    {
        $session = new Session();
        $session->delete('user_id');

        $this->redirect('index.php');
    }
}
