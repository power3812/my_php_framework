<div class="paginator">
  <ul>
    <?php if ($paginator->previousPageExist()) : ?>
      <li><a href="<?php echo $paginator->createUri() ?>">&laquo;</a></li>
      <li><a href="<?php echo h($paginator->createUri($paginator->getPreviousPage())) ?>">&lt;</a></li>
    <?php endif ?>
    <?php foreach ($paginator->getViewPageNumbers() as $view_page_number) : ?>
      <?php if ($paginator->isCurrentPage($view_page_number)) : ?>
        <li><?php echo h($view_page_number) ?></li>
      <?php else : ?>
        <li><a href="<?php echo h($paginator->createUri($view_page_number)) ?>"><?php echo h($view_page_number) ?></a></li>
      <?php endif ?>
    <?php endforeach ?>
    <?php if ($paginator->nextPageExist()) : ?>
      <li><a href="<?php echo h($paginator->createUri($paginator->getNextPage())) ?>">&gt;</a></li>
      <li><a href="<?php echo h($paginator->createUri($paginator->getMaxPageNumber())) ?>">&raquo;</a></li>
    <?php endif ?>
  </ul>
</div>
