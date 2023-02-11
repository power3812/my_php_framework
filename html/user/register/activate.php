<?php include(HTML_FILES_DIR . '/user/common/header.php') ?>
<?php include(HTML_FILES_DIR . '/user/common/error.php') ?>

<?php if ($is_registration_completed) : ?>
  <div class="registration_completed">
    会員登録ありがとうございます。<br>
    会員登録が完了しました。<br>
  </div>
<?php endif ?>
<div class="back_top">
  <a href="/">Topに戻る</a>
</div>

<?php include(HTML_FILES_DIR . '/user/common/footer.php') ?>
