<?php include(HTML_FILES_DIR . '/common/error.php') ?>

<?php $_action = (isset($is_edit_form)) ? 'edit.php' : 'post.php' ?>
<form class="default" action="<?php echo get_uri($_action) ?>" method="post" enctype="multipart/form-data">
  <div class="item">
    <p class="title">
      ユーザー名(オプション)
    </p>
    <p class="input">
      <input type="text" name="username" value="<?php if (isset($username)) echo h($username) ?>" />
    </p>
  </div>
  <div class="item">
    <p class="title">
      タイトル
    </p>
    <p class="input">
      <input type="text" name="title" value="<?php if (isset($title)) echo h($title) ?>" />
    </p>
  </div>
  <div class="item">
    <p class="title">
      メッセージ
    </p>
    <p class="input">
      <textarea style="height: 80px;" name="message"><?php if (isset($message)) echo h($message) ?></textarea>
    </p>
  </div>
  <div class="item">
    <p class="title">
      画像(オプション)
    </p>
    <p class="input">
      <input type="file" name="image" />
    </p>
  </div>
  <?php if (isset($is_edit_form)) : ?>
    <?php if (!is_empty($current_image)) : ?>
      <div class="item">
        <p class="title">
          現在の画像
        </p>
        <p class="input">
          <img class="photo" src="<?php echo $image_dir ?>/<?php echo $current_image ?>" /><br />
          <input id="cpd" type="checkbox" name="del_image" value="1" />
          <label for="cpd">画像を削除</label>
        </p>
      </div>
    <?php endif ?>
    <div class="submit">
      <input type="hidden" name="do_edit" value="1" />
      <input type="hidden" name="post_id" value="<?php if (isset($post_id)) echo $post_id ?>" />
      <input type="hidden" name="page" value="<?php if (isset($page)) echo h($page) ?>" />
      <input type="hidden" name="password" value="<?php if (isset($password)) echo h($password) ?>" />
      <input type="submit" value="&raquo; 編集" />
      <input type="button" value="&raquo; キャンセル" onclick="window.location.href='<?php echo get_uri('index.php') ?>?page=<?php echo $page ?>';">
    </div>
  <?php else : ?>
    <?php if (!$is_login) : ?>
      <div class="item">
        <p class="title">
          パスワード (オプション)
        </p>
        <p class="input">
          <input type="password" name="password" />
        </p>
      </div>
    <?php endif ?>
    <div class="submit">
      <input type="submit" value="&raquo; 投稿" />
    </div>
  <?php endif ?>
</form>
