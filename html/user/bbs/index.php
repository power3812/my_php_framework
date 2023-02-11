<?php include(HTML_FILES_DIR . '/user/common/header.php') ?>

<?php include(HTML_FILES_DIR . '/user/bbs/form.php') ?>

<div id="contents">
  <?php if ($posts) : ?>
    <div class="comments">
      <?php foreach ($posts as $post) : ?>
        <div class="comment">
          <div class="username">
            <?php if (!is_empty($post['username'])) : ?>
              <?php echo h($post['username']) ?>
            <?php else : ?>
              名無し
            <?php endif ?>
            <?php if (isset($post['user_id'])) : ?>
              (会員)[ID:<?php echo h($post['user_id']) ?>]
            <?php else : ?>
              (無会員)
            <?php endif ?>
          </div>
          <div class="title">
            <?php echo h($post['title']) ?>
          </div>
          <div class="body">
            <?php echo nl2br(h($post['message'])) ?>
          </div>
          <?php if (!is_empty($post['image'])) : ?>
            <div class="photo">
              <a href="<?php echo get_uri($image_dir . '/' . $post['image']) ?>" target="_blank">
                <img src="<?php echo get_uri($image_dir . '/' . $post['image']) ?>" />
              </a>
            </div>
          <?php endif ?>
          <div class="date">
            <?php echo h($post['created_at']) ?>
          </div>
          <form id="af<?php echo $post['id'] ?>" class="action_form" action="" method="post">
            <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>" />
            <input type="hidden" name="page" value="<?php echo $paginator->getCurrentPage() ?>" />
            <?php if ($is_login) : ?>
              <?php if ($post['user_id'] === $login_user['id']) : ?>
                <input type="hidden" name="is_user_id_match" value="1" />
                <div class="submit">
                  <input type="submit" name="submit" value="&raquo; 削除" formaction="<?php echo get_uri('delete') ?>" formmethod="post" />
                  <input type="submit" name="submit" value="&raquo; 編集" formaction="<?php echo get_uri('edit') ?>" formmethod="post" />
                </div>
              <?php endif ?>
            <?php else : ?>
              <?php if (is_empty($post['user_id'])) : ?>
                <input type="password" name="password" value="" />
                <div class="submit">
                  <input type="submit" name="submit" value="&raquo; 削除" formaction="<?php echo get_uri('delete') ?>" formmethod="post" />
                  <input type="submit" name="submit" value="&raquo; 編集" formaction="<?php echo get_uri('edit') ?>" formmethod="post" />
                </div>
              <?php endif ?>
            <?php endif ?>
          </form>
        </div>
      <?php endforeach ?>
    </div>
  <?php endif ?>

  <?php include(HTML_FILES_DIR . '/common/paginator.php') ?>
</div>

<?php include(HTML_FILES_DIR . '/user/common/footer.php') ?>
