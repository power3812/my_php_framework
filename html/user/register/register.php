<?php include(HTML_FILES_DIR . '/user/common/header.php') ?>
<?php include(HTML_FILES_DIR . '/common/error.php') ?>

<form class="default" action="<?php echo get_uri('registerPost.php') ?>" method="post">
  <div class="item">
    <p class="title">
      ユーザー名
    </p>
    <p class="input">
      <input type="text" name="username" value="<?php if (isset($username)) echo h($username) ?>" />
    </p>
  </div>
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
    <input type="submit" value="&raquo; 登録" />
  </div>
</form>

<?php include(HTML_FILES_DIR . '/user/common/footer.php') ?>
