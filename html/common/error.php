<?php if (!empty($errors)) : ?>
  <ul class="error">
    <?php if (is_array($errors)) : ?>
      <?php foreach ($errors as $_error) : ?>
      <li><?php echo h($_error) ?></li>
      <?php endforeach ?>
    <?php else : ?>
      <li><?php echo h($errors) ?></li>
    <?php endif ?>
  </ul>
<?php endif ?>
