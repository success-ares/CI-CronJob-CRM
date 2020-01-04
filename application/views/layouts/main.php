<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Evando Junior">

        <title>Medic</title>
        <!-- Bootstrap core CSS -->
        <link href="<?= base_url('resources/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('resources/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?= base_url('resources/css/custom.css') ?>" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="overlay hidden"><img src="<?= base_url('resources/images/ajax-loader.gif') ?>" > </div>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Medic</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <!--<li class="active"><a href="#">Home</a></li>-->
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <?php
        if (isset($_view) && $_view)
            $this->load->view($_view);
        ?>                    

        <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
        
        <script src="<?= base_url('resources/js/jquery.min.js') ?>"></script>
        <script src="<?= base_url('resources/js/bootstrap.min.js') ?>"></script>
        <script src="<?= base_url('resources/js/custom.js') ?>"></script>
    </body>
</html>
