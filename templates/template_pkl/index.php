<!DOCTYPE html>
<html lang="en">
  <head><?php echo $sys->meta();?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php echo $sys->block_show('top');?>
      <div>
        <?php if (_ADMIN) echo $sys->block_show('left');?>
        <?php echo $sys->block_show('content_top');?>
        <?php echo trim($Bbc->content);?>
        <?php echo $sys->block_show('content_bottom');?>
        <?php echo $sys->block_show('footer'); ?>
      </div>
    <!-- Bootstrap JavaScript -->
    <script src="<?php echo _URL;?>templates/admin/bootstrap/js/bootstrap.min.js"></script>
    
<!-- 
    <script src="<?php echo _URL;?>templates/admin/bootstrap/js/bootstrap4.min.js" type="text/javascript"></script> -->
    
  </body>
</html>
