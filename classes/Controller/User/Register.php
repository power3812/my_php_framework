<?php

class Controller_User_Register extends Controller_User_AppBase
{
    protected $token_expires = 60 * 60 * 24;

    public function register()
    {
        $this->render('user/register/register.php', get_defined_vars());
    }

    public function registerPost()
    {
        if ($this->is_login) {
            $this->redirect('index.php');
        }

        $username    = $this->getParam('username');
        $email       = $this->getParam('email');
        $password    = $this->getParam('password');
        $do_register = $this->getParam('do_register');

        $data = [
            'name'     => $username,
            'email'    => $email,
            'password' => $password,
        ];

        $errors       = [];
        $storage_user = new Storage_User();
        $validator    = new Validator($storage_user->getInputRules());
        $errors       = $validator->validate($data);
        $is_send_mail = false;

        if (is_empty($errors) && !is_empty($do_register)) {
            if (!$storage_user->isEmailExists($email)) {
                $token = hash('sha256', uniqid(mt_rand()));
                $url   = 'http://' . DOMAIN . '/activate.php?token=' . $token;

                $data['token']        = $token;
                $data['expires_date'] = date('Y-m-d H:i:s', time() + $this->token_expires);

                $storage_pre_user = new Storage_PreUser();
                $storage_pre_user->insert($data);

                $subject = 'bbs会員登録用URL';

                $message = <<<MESSAGE
{$username}さん
会員登録を完了させるために下記URLを24時間以内にクリックして下さい。
{$url}
MESSAGE;

                $is_send_mail = mb_send_mail($email, $subject, $message);
            } else {
                $is_send_mail = true;
            }

            if (!$is_send_mail) {
                $errors[] = '会員登録メールの送信に失敗しました。もう一度やり直して下さい。';
            } else {
                $this->redirect('registerFinish.php');
            }
        }

        if (empty($errors)) {
            $this->render('user/register/registerPost.php', get_defined_vars());
        } else {
            $this->render('user/register/register.php', get_defined_vars());
        }
    }

    public function registerFinish()
    {
        if ($this->is_login) {
            $this->redirect('index.php');
        }

        $this->render('user/register/registerFinish.php', get_defined_vars());
    }

    public function activate()
    {
        if ($this->is_login) {
            $this->redirect('index.php');
        }

        $token                     = $this->getParam('token');
        $errors                    = [];
        $is_registration_completed = false;

        if (is_empty($token)) {
            $this->err404();
        }

        if (is_empty($errors)) {
            $storage_user     = new Storage_User();
            $storage_pre_user = new Storage_PreUser();
            $pre_user         = $storage_pre_user->selectOne(
                ['*'],
                [
                    [
                        'column' => 'token',
                        'value'  => $token,
                    ],
                    [
                        'column' => 'flag',
                        'value'  => 0,
                    ],
                    [
                        'column'   => 'expires_date',
                        'operator' => '>=',
                        'value'    => date('Y-m-d H:i:s'),
                    ],
                ]
            );

            if (!is_empty($pre_user)) {
                $storage_pre_user->update(
                    [
                        'flag' => 1,
                    ],
                    [
                        [
                            'column' => 'token',
                            'value'  => $token,
                        ],
                    ]
                );

                $is_registration_completed = $storage_user->insert(
                    [
                        'name'     => $pre_user['name'],
                        'email'    => $pre_user['email'],
                        'password' => $pre_user['password'],
                    ]
                );

                if ($is_registration_completed) {
                    $user = $storage_user->selectOne(
                        ['*'],
                        [
                            [
                                'column' => 'email',
                                'value'  => $pre_user['email'],
                            ]
                        ],
                    );
                } else {
                    $errors[] = 'ユーザー登録に失敗しました。もう一度最初からやり直して下さい。';
                }
            } else {
                $errors[] = '無効なURLです。もう一度最初からやり直して下さい。';
            }

            if (!is_empty($user)) {
                $session = new Session();
                $session->set('id', $user['id']);
            } else {
                $errors[] = 'ユーザー登録に失敗しました。もう一度やり直して下さい。';
            }
        }

        $this->render('user/register/activate.php', get_defined_vars());
    }
}
