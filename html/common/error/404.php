<div id="contents">
  <h2>404 Not Found</h2>
  <div>
    The requested URL <?php if (isset($requestUri)) echo $requestUri ?> was not found on this server.<br />
    <?php if ($message) : ?>
      <?php echo $message ?>
    <?php endif ?>
  </div>
</div>
