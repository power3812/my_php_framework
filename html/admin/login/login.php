<?php include(HTML_FILES_DIR . '/admin/common/header.php') ?>
<?php include(HTML_FILES_DIR . '/common/error.php') ?>

<form class="default" action="<?php echo get_uri('admin/login') ?>" method="post">
  <div class="item">
    <p class="title">
      管理者ID
    </p>
    <p class="input">
      <input type="text" name="login_id" value="<?php if (isset($login_id)) echo h($login_id) ?>" />
    </p>
  </div>
  <div class="item">
    <p class="title">
      パスワード
    </p>
    <p class="input">
      <input type="password" name="password" />
    </p>
  </div>
  <div class="submit">
    <input type="hidden" name="do_submit" value="1" />
    <input type="submit" value="&raquo; ログイン" />
  </div>
</form>

