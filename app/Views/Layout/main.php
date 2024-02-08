<!DOCTYPE html>
<html>
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OS - <?= $this->renderSection("title") ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="<?= site_url("resources/plugin/bootstrap/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= site_url("resources/plugin/font-awesome/css/font-awesome.min.css") ?>">
    <link rel="stylesheet" href="<?= site_url("resources/css/font.css") ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <link rel="stylesheet" href="<?= site_url("resources/css/style.default.css") ?>" id="theme-stylesheet">
    <link rel="stylesheet" href="<?= site_url("resources/css/custom.css") ?>">
    <link rel="shortcut icon" href="img/favicon.ico">

    <?= $this->renderSection("styles") ?>
  </head>
  <body>
    
    <?= $this->include('Layout/_partials/navbar') ?>

    <div class="d-flex align-items-stretch">
      
      <?= $this->include('Layout/_partials/sidebar') ?>

      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom"><?= $title ?></h2>
          </div>
        </div>
        
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <?= $this->include('Layout/_messages') ?>
            <?= $this->renderSection("content") ?>
          </div>
        </section>

        <?= $this->include('Layout/_partials/footer') ?>
      </div>
    </div>
    
    <!-- JavaScript files-->
    <script src="<?= site_url("resources/plugin/jquery/jquery.min.js") ?>"></script>
    <script src="<?= site_url("resources/plugin/popper.js/umd/popper.min.js") ?>"> </script>
    <script src="<?= site_url("resources/plugin/bootstrap/js/bootstrap.min.js") ?>"></script>
    <script src="<?= site_url("resources/js/front.js") ?>"></script>

    <script>
      $(function() {
        $('[data-toggle="popover"]').popover({
          html: true,
        });
      });
    </script>

    <?= $this->renderSection("scripts") ?>
  </body>
</html>