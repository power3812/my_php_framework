<?php include(HTML_FILES_DIR . '/admin/common/header.php') ?>

<div id="contents">
  <form class="default" action="<?php echo get_uri('admin/index.php') ?>" method="get">
    <div>
      <p>タイトル <input type="text" name="title" value="<?php if (!is_empty($title)) echo h($title) ?>" /></p>
      <p>メッセージ <input type="text" name="message" value="<?php if (!is_empty($message)) echo h($message) ?>" /></p>
      <p>
        画像
        <label><input type="radio" name="image" value="with" <?php if ($image === 'with') echo h('checked="checked"') ?> />あり</label>
        <label><input type="radio" name="image" value="without" <?php if ($image === 'without') echo h('checked="checked"') ?> />なし</label>
        <label><input type="radio" name="image" value="unspecified" <?php if ($image === 'unspecified') echo h('checked="checked"') ?> />指定しない</label>
      </p>
      <p>
        ステータス
        <label><input type="radio" name="status" value="on" <?php if ($status === 'on') echo h('checked="checked"') ?> />存在する</label>
        <label><input type="radio" name="status" value="delete" <?php if ($status === 'delete') echo h('checked="checked"') ?> />削除済み</label>
        <label><input type="radio" name="status" value="unspecified" <?php if ($status === 'unspecified') echo h('checked="checked"') ?> />指定しない</label>
      </p>
      <input type="submit" value="&raquo; 検索" />
    </div>
  </form>
  <form id="js-form">
    <table border="1">
      <tr>
        <td align="center">
          <input type="checkbox" id="js-select-all" onclick="check_all()" />
        </td>
        <td align="center">ID</td>
        <td align="center">タイトル</td>
        <td align="center">メッセージ</td>
        <td align="center">画像</td>
        <td align="center">日付</td>
        <td></td>
      </tr>
      <?php foreach ($posts as $post) : ?>
        <tr <?php if ($post['is_deleted']) echo 'style="background: gray;"' ?>>
          <td align="center">
          <?php if ($post['is_deleted'] !== 1) : ?>
            <input type="checkbox" class="js-select" name="post_ids[]" value="<?php echo h($post['id']) ?>" onclick="check()" />
          <?php endif ?>
          </td>
          <td align="center">
            <?php echo h($post['id']) ?>
          </td>
          <td style="word-break: break-all;">
            <?php echo h($post['title']) ?>
          </td>
          <td style="word-break: break-all;" >
            <?php echo nl2br(h($post['message'])) ?>
          </td>
          <td align="center">
            <?php if (!is_empty($post['image'])) : ?>
              <div class="photo">
                <a href="<?php echo get_uri($image_dir . '/' . $post['image']) ?>" target="_blank">
                  <img src="<?php echo get_uri($image_dir . '/' . $post['image']) ?>" />
                </a>
              </div>
              <div class="submit">
                <input type="submit" value="&raquo; 削除" onclick="delete_image(<?= $post['id'] ?>)" />
              </div>
            <?php endif ?>
          </td>
          <td align="center">
            <?php echo h($post['created_at']) ?>
          </td>
          <td align="center">
            <div class="submit">
              <?php if ($post['is_deleted'] === 1) : ?>
                <input type="submit" value="&raquo; リカバリ" onclick="recovery(<?= $post['id'] ?>)" />
              <?php else : ?>
                <input type="submit" value="&raquo; 削除" onclick="one_delete(<?= $post['id'] ?>)" />
              <?php endif ?>
            </div>
          </td>
        </tr>
      <?php endforeach ?>
    </table>
    <div class="submit">
      <input id="all_delete" type="submit" value="&raquo; 選択した投稿一括削除" onclick="bulk_delete()"/>
    </div>
    <input type="hidden" name="post_id" value="" />
    <input type="hidden" name="page" value="<?php echo h($page) ?>" />
    <input type="hidden" name="title" value="<?php echo h($title) ?>" />
    <input type="hidden" name="message" value="<?php echo h($message) ?>" />
    <input type="hidden" name="image" value="<?php echo h($image) ?>" />
    <input type="hidden" name="status" value="<?php echo h($status) ?>" />
  </form>
</div>

<?php include(HTML_FILES_DIR . '/common/paginator.php') ?>
<?php include(HTML_FILES_DIR . '/admin/common/footer.php') ?>

<script type="text/javascript" src="<?php echo get_uri('js/admin/bbs/index.js') ?>"></script>
