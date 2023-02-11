<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Bulletin Board System</title>
  <link type="text/css" rel="stylesheet" href="<?php echo get_uri('css/user/reset.css') ?>" />
  <link type="text/css" rel="stylesheet" href="<?php echo get_uri('css/user/default.css') ?>" />
</head>

<body>
  <div id="header">
    <h1><a href="<?php echo get_uri('/') ?>">Bulletin Board System</a></h1>
  </div>
  <?php if (!$is_login) : ?>
    <div class="move">
      <a href="<?php echo get_uri('login') ?>">ログイン</a>
      <a href="<?php echo get_uri('register') ?>">登録</a>
    </div>
  <?php else : ?>
    <div class="move">
      <p>こんにちは、<?php echo h($login_user['name']) ?></p>
    </div>
    <div class="move">
      <a href="<?php echo get_uri('logout') ?>">ログアウト</a>
    </div>
  <?php endif ?>
