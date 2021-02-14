<?php include(HTML_FILES_DIR . '/user/common/header.php') ?>

<div id="contents">
  <?php include(HTML_FILES_DIR . '/common/error.php') ?>
  <div class="confirm_form">
    <?php if (is_empty($post['password']) || !$is_password_match) : ?>
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
      <?php if (is_empty($post['password']) && ($login_user['id'] !== $post['user_id'])) : ?>
        <form class="default" action="<?php echo get_uri('index.php') ?>" method="get">
          <div class="message">
            この投稿は編集できません。
          </div>
          <div class="submit">
            <input type="hidden" name="page" value="<?php echo $page ?>" />
            <input type="submit" value="&raquo; 戻る">
          </div>
        </form>
      <?php endif ?>
    <?php endif ?>
    <?php if (!is_empty($post['password']) || ($login_user['id'] === $post['user_id'])) : ?>
      <?php if ($is_password_match || ($login_user['id'] === $post['user_id'])) : ?>
        <?php include(HTML_FILES_DIR . '/user/bbs/form.php') ?>
      <?php else : ?>
        <form class="default" action="<?php echo get_uri('edit.php') ?>" method="post">
          <div class="submit">
            <input type="hidden" name="post_id" value="<?php echo $post_id ?>" />
            <input type="hidden" name="page" value="<?php echo $page ?>" />
            <input type="password" name="password" value="<?php echo $password ?>" />
            <input type="submit" value="&raquo; 編集" />
          </div>
        </form>
      <?php endif ?>
    <?php endif ?>
  </div>
</div>

<?php include(HTML_FILES_DIR . '/user/common/footer.php') ?>
