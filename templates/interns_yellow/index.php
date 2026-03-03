<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');
$is_admin = (defined('_ADMIN') && _ADMIN != '') ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $sys->meta(); ?>
</head>

<body class="<?php echo $is_admin ? 'admin-layout' : ''; ?>">

  <?php echo $sys->block_show('top'); ?>

  <?php if ($is_admin): ?>

    <div class="admin-wrapper">

      <aside class="admin-sidebar">
        <?php echo $sys->block_show('left'); ?>
      </aside>

      <main class="admin-content">
        <div class="admin-content-top">
          <?php echo $sys->block_show('content_top'); ?>
        </div>

        <div class="admin-main">
          <?php echo trim($Bbc->content); ?>
        </div>

        <div class="admin-footer">
          <?php echo $sys->block_show('footer'); ?>
        </div>
      </main>

    </div>

  <?php else: ?>

    <div>
      <?php echo $sys->block_show('content_top'); ?>
      <?php echo trim($Bbc->content); ?>
      <?php echo $sys->block_show('footer'); ?>
    </div>

  <?php endif; ?>

  <?php
  $sys->link_js($sys->template_url . '../admin/bootstrap/js/bootstrap.min.js', false);
  ?>

</body>

</html>