<?php include(HTML_FILES_DIR . '/user/common/header.php') ?>
<?php include(HTML_FILES_DIR . '/common/error.php') ?>

<form class="default" action="<?php echo get_uri('login.php') ?>" method="post">
  <div class="item">
    <p class="title">
      メールアドレス
    </p>
    <p class="input">
      <input type="email" name="email" value="<?php if (isset($email)) echo h($email) ?>" />
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

<?php include(HTML_FILES_DIR . '/user/common/footer.php') ?>
