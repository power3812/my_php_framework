<?php

class Controller_Admin_Bbs extends Controller_Admin_AppBase
{
    const PAGINATOR_MAX_VIEW_NUMBER = 10;
    const PAGINATOR_ITEMS_PER_PAGE  = 20;

    protected $image_dir = Uploader_File::UPLOAD_DIR_NAME . '/bbs';

    public function index()
    {
        if (!$this->is_login) {
            $this->redirect('admin/login.php');
        }

        $bbs = new Storage_Bbs();

        $page    = $this->getParam('page');
        $title   = $this->getParam('title');
        $message = $this->getParam('message');
        $image   = $this->getParam('image');
        $status  = $this->getParam('status');

        $where     = $this->createConditions($title, $message, $image, $status);
        $paginator = $this->createPaginator($bbs->count($where), $this->getParams());

        try {
            $paginator->setCurrentPage($page);
        } catch (Exception $e) {
            $this->err404();
        }

        $posts = $bbs->select(
            ['*'],
            $where,
            [
                'id' => 'DESC'
            ],
            $paginator->getItemsPerPage(),
            $paginator->getOffset()
        );

        $this->render('admin/bbs/index.php', get_defined_vars());
    }

    public function delete()
    {
        if (!$this->is_login) {
            $this->redirect('admin/login.php');
        }

        $post_ids = $this->getParam('post_ids');

        $bbs = new Storage_Bbs();

        if (is_empty($post_ids)) {
            $this->redirect('admin/index.php');
        }

        $posts = $bbs->selectByIds($post_ids);

        if (is_empty($posts)) {
            $this->err404();
        }

        foreach ($posts as $post) {
            if (!is_empty($post['image'])) {
                $this->createImageUploader()->delete($post['image'], true);
            }
        }

        $bbs->bulkSoftDelete($post_ids);

        $this->redirect('admin/index.php', ['page' => $this->getPage()]);
    }

    public function recovery()
    {
        if (!$this->is_login) {
            $this->redirect('admin/login.php');
        }

        $post_id = $this->getParam('post_id');

        $bbs  = new Storage_Bbs();
        $post = $bbs->selectById($post_id);

        $bbs->softRecovery($post_id);

        $this->redirect('admin/index.php', ['page' => $this->getPage()]);
    }

    public function deleteImage()
    {
        if (!$this->is_login) {
            $this->redirect('admin/login.php');
        }

        $post_id = $this->getParam('post_id');

        $bbs  = new Storage_Bbs();
        $post = $bbs->selectById($post_id);

        if (is_empty($post)) {
            $this->err404();
        }

        if (!is_empty($post['image'])) {
            $this->createImageUploader()->delete($post['image'], true);
            $bbs->deleteImage($post_id);
        }

        $this->redirect('admin/index.php', ['page' => $this->getPage()]);
    }

    protected function createConditions($title = null, $message = null, $image = 3, $status = 3)
    {
        $where = [];

        if ($title !== null) {
            $where[] = [
                'column'   => 'title',
                'operator' => 'LIKE',
                'value'    => '%' . $title . '%'
            ];
        }

        if ($message !== null) {
            $where[] = [
                'column'   => 'message',
                'operator' => 'LIKE',
                'value'    => '%' . $message . '%'
            ];
        }

        if ($image === 'with') {
            $where[] = [
                'column'   => 'image',
                'operator' => 'IS_NOT_NULL',
            ];
        } elseif ($image === 'without') {
            $where[] = [
                'column'   => 'image',
                'operator' => 'IS_NULL',
            ];
        }

        if ($status === 'on') {
            $where[] = [
                'column' => 'is_deleted',
                'value'  => '0'
            ];
        } elseif ($status === 'delete') {
            $where[] = [
                'column' => 'is_deleted',
                'value'  => '1'
            ];
        }

        return $where;
    }

    protected function createPaginator(int $items_count, $params = [])
    {
        $paginator = new Paginator(
            $items_count,
            self::PAGINATOR_MAX_VIEW_NUMBER,
            self::PAGINATOR_ITEMS_PER_PAGE
        );

        $paginator->setUri($this->getEnv('Request-Uri'), $this->getParams());

        return $paginator;
    }

    protected function createImageUploader()
    {
        return new Uploader_Image($this->image_dir);
    }
}
