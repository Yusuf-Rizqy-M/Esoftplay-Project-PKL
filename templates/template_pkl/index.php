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
    <div class="container-fluid" style="padding-bottom: 70px;">
      <div class="col-md-2 col-lg-2 pull-right">
        <?php echo $sys->block_show('right');?>
      </div>
      <div class="col-md-10 col-lg-10 pull-left">
        <?php echo $sys->block_show('content_top');?>
        <?php echo trim($Bbc->content);?>
        <?php echo $sys->block_show('content_bottom');?>
        <?php echo $sys->block_show('footer'); ?>
      </div>
      <div class="clearfix"></div>
      <div class="navbar navbar-default navbar-fixed-bottom">
        <div class="container">
          <p class="navbar-text pull-left"><?php echo config('site','footer');?></p>
          <a href=http://www.dev.esoftplay.com/"https://t.me/esoftplay" class="navbar-btn btn-primary btn pull-right">
            <span class="glyphicon glyphicon-ok"></span> Notify me on Update!
           </a>
        </div>
      </div>
    </div>
    <!-- Bootstrap JavaScript -->
    <script src="<?php echo _URL;?>templates/admin/bootstrap/js/bootstrap.min.js"></script>
    

    <script src="<?php echo _URL;?>templates/admin/bootstrap/js/bootstrap4.min.js" type="text/javascript"></script>
    
  </body>
</html>