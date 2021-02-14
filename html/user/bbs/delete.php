<?php include(HTML_FILES_DIR . '/user/common/header.php') ?>

<div id="contents">
  <?php include(HTML_FILES_DIR . '/common/error.php') ?>

  <div class="comments">
    <div class="comment">
      <div class="title">
        <?php echo h($post['title']) ?>
      </div>
      <div class="body">
        <?php echo nl2br(h($post['message'])) ?>
      </div>
      <?php if (!is_empty($post['image']) && file_exists($image_dir . '/' . $post['image'])) : ?>
        <div class="photo">
          <a href="<?php echo get_uri($image_dir . '/' . $post['image']) ?>" target="_blank">
            <img src="<?php echo $image_dir ?>/<?php echo $post['image'] ?>" />
          </a>
        </div>
      <?php endif ?>
      <div class="date">
        <?php echo h($post['created_at']) ?>
      </div>
    </div>
  </div>
  <div class="confirm_form">
    <?php if (!is_empty($post['password']) || ($login_user['id'] === $post['user_id'])) : ?>
      <form class="default" action="<?php echo get_uri('delete.php') ?>" method="post">
        <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>" />
        <input type="hidden" name="page" value="<?php echo $page ?>" />
        <input type="hidden" name="password" value="<?php echo $password ?>" />
        <?php if (is_empty($errors)) : ?>
          <div class="message">
            本当に削除しますか？
          </div>
          <div class="submit">
            <input type="hidden" name="do_delete" value="1" />
            <input type="submit" value="&raquo; 削除" />
            <input type="button" value="&raquo; キャンセル" onclick="window.location.href='<?php echo get_uri('index.php') ?>?page=<?php echo $page ?>';">
          </div>
        <?php else : ?>
          <div class="submit">
            <input type="password" name="password" />
            <input type="submit" value="&raquo; 削除" />
          </div>
        <?php endif ?>
      </form>
    <?php else : ?>
      <form class="default" action="<?php echo get_uri('index.php') ?>" method="get">
        <div class="message">
          この投稿は削除できません。
        </div>
        <div class="submit">
          <input type="hidden" name="page" value="<?php echo $page ?>" />
          <input type="submit" value="&raquo; 戻る">
        </div>
      </form>
    <?php endif ?>
  </div>
</div>

<?php include(HTML_FILES_DIR . '/user/common/footer.php') ?>
