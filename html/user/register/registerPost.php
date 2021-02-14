<?php include(HTML_FILES_DIR . '/user/common/header.php') ?>

<div class="user_input_registration">
  <p>ユーザー名:<?php echo h($username) ?></p>
  <p>メールアドレス:<?php echo h($email) ?></p>
  <p>パスワード:<?php echo h($password) ?></p>
  <form class="default" action="<?php echo get_uri('registerPost.php') ?>" method="post">
    <div class="submit">
      <input type="hidden" name="username" value="<?php echo h($username) ?>" />
      <input type="hidden" name="email" value="<?php echo h($email) ?>" />
      <input type="hidden" name="password" value="<?php echo h($password) ?>" />
      <input type="hidden" name="do_register" value="1" />
      <input type="submit" value="&raquo; 登録" />
    </div>
  </form>
  <div class="back_page">
    <a href="register.php">&raquo; 戻る</a>
  <div>
</div>

<?php include(HTML_FILES_DIR . '/user/common/footer.php') ?>
