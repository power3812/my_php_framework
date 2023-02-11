<?php

class Controller_User_Bbs extends Controller_User_AppBase
{
    const PAGINATOR_MAX_VIEW_NUMBER = 5;
    const PAGINATOR_ITEMS_PER_PAGE  = 10;

    protected $image_dir = Uploader_File::UPLOAD_DIR_NAME . '/bbs';

    public function index()
    {
        $bbs       = new Storage_Bbs();
        $paginator = $this->createPaginator($bbs->count(
            [
                [
                    'column' => 'is_deleted',
                    'value'  => 0,
                ],
            ]
        ));

        try {
            $paginator->setCurrentPage($this->getPage());
        } catch (Exception $e) {
            $this->err404();
        }

        $posts = $bbs->select(
            ['*'],
            [
                [
                    'column' => 'is_deleted',
                    'value'  => 0,
                ],
            ],
            [
                'created_at' => 'DESC'
            ],
            $paginator->getItemsPerPage(),
            $paginator->getOffset()
        );

        $username = ($this->is_login) ? $this->login_user['name'] : null;

        $this->render('user/bbs/index.php', get_defined_vars());
    }

    public function post()
    {
        $username = $this->getParam('username');
        $title    = $this->getParam('title');
        $message  = $this->getParam('message');

        $data = [
            'username' => $username,
            'title'    => $title,
            'message'  => $message,
        ];

        if ($this->is_login) {
            $data['user_id'] = $this->login_user['id'];
        } else {
            $data['password'] = $this->getParam('password');
        }

        $bbs        = new Storage_Bbs();
        $validator  = new Validator($bbs->getInputRules());
        $errors     = $validator->validate($data);
        $uploader   = $this->createImageUploader();
        $image_file = $this->getFile('image');

        if (!is_empty($image_file)) {
            $image = $image_file['tmp_name'];
        } else {
            $image = null;
        }

        $has_image = !is_empty($image_file);

        if ($has_image) {
            $errors = array_merge($errors, $validator->validate(['image' => $image]));
        }

        if (is_empty($errors)) {
            if ($has_image) {
                $data['image'] = $uploader->uploadImage($image_file['data']);
            } else {
                $data['image'] = null;
            }

            $bbs->insert($data);
            $this->redirect('');
        }

        $this->render('user/bbs/post.php', get_defined_vars());
    }

    public function delete()
    {
        $post_id  = $this->getParam('post_id');
        $password = $this->getParam('password');

        if (is_empty($post_id)) {
            $this->err400();
        }

        $bbs               = new Storage_Bbs();
        $post              = $bbs->selectById($post_id);
        $page              = $this->getPage();
        $errors            = [];
        $is_password_set   = false;
        $is_password_match = false;

        if (
            is_empty($post)           ||
            $post['is_deleted'] === 1 ||
            ($this->is_login && ($this->login_user['id'] !== $post['user_id']))
        ) {
            $this->err404();
        }

        if (!is_empty($post['password'])) {
            $is_password_set = true;
        }

        if (!$this->is_login) {
            if ($is_password_set) {
                if (password_verify($password, $post['password'])) {
                    $is_password_match = true;
                } else {
                    $errors[] = 'パスワードが違います。入力し直してください。';
                }
            } else {
                $errors[] = 'パスワードが設定されていないため編集できません。';
            }
        } else {
            $is_password_match = true;
        }

        if ($is_password_match && ($this->getParam('do_delete') !== null)) {
            if (!is_empty($post['image'])) {
                $this->createImageUploader()->delete($post['image'], true);
            }

            $bbs->softDelete($post_id);

            $this->redirect('', ['page' => $this->getPage()]);
        }

        $username = ($this->is_login) ? $this->login_user['name'] : $post['username'];

        $this->render('user/bbs/delete.php', get_defined_vars());
    }

    public function edit()
    {
        $post_id  = (int) $this->getParam('post_id');
        $password = $this->getParam('password');

        if (is_empty($post_id)) {
            $this->err400();
        }

        $bbs               = new Storage_Bbs();
        $post              = $bbs->selectById($post_id);
        $title             = $post['title'];
        $message           = $post['message'];
        $current_image     = $post['image'];
        $is_edit_form      = true;
        $is_password_set   = false;
        $is_password_match = false;
        $errors            = [];

        if (
            is_empty($post)           ||
            $post['is_deleted'] === 1 ||
            ($this->is_login && ($this->login_user['id'] !== $post['user_id']))
        ) {
            $this->err404();
        }

        if (!is_empty($post['password'])) {
            $is_password_set = true;
        }

        if (!$this->is_login) {
            if ($is_password_set) {
                if (password_verify($password, $post['password'])) {
                    $is_password_match = true;
                } else {
                    $errors[] = 'パスワードが違います。入力し直してください。';
                }
            } else {
                $errors[] = 'パスワードが設定されていないため編集できません。';
            }
        } else {
            $is_password_match = true;
        }

        if ($is_password_match && $this->getParam('do_edit') !== null) {
            $image_file = $this->getFile('image');
            $validator  = new Validator($bbs->getInputRules());
            $uploader   = $this->createImageUploader();
            $image      = $this->getFilePath('image');

            $has_image       = !is_empty($image);
            $do_delete_image = ($this->getParam('delete_image') !== null);

            if (!$do_delete_image && $has_image) {
                $errors = array_merge($errors, $validator->validate(['image' => $image]));
            }

            if (is_empty($errors)) {
                $update_posts            = [];
                $update_posts['title']   = $this->getParam('title');
                $update_posts['message'] = $this->getParam('message');

                if ($do_delete_image) {
                    $uploader->delete($current_image, true);
                    $image = null;
                } elseif ($image_file !== null) {
                    $image = $uploader->uploadImage($image_file['data']);

                    if (!is_empty($post['image'])) {
                        $uploader->delete($current_image, true);
                    }
                }

                $update_posts['image'] = $image;

                $bbs->updateById($post_id, $update_posts);

                $this->redirect('', ['page' => $this->getPage()]);
            }
        }

        $username = ($this->is_login) ? $this->login_user['name'] : $post['username'];

        $this->render('user/bbs/edit.php', get_defined_vars());
    }

    protected function createPaginator(int $items_count)
    {
        $paginator = new Paginator(
            $items_count,
            self::PAGINATOR_MAX_VIEW_NUMBER,
            self::PAGINATOR_ITEMS_PER_PAGE
        );

        $paginator->setUri($this->getEnv('Request-Uri'));

        return $paginator;
    }

    protected function createImageUploader()
    {
        return new Uploader_Image($this->image_dir);
    }
}
