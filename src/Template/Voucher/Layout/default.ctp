<?php $cakeDescription = 'Business Zakoopi';?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?= $cakeDescription ?>:
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="Business Zakoopi">
        <meta name="description" content="Business Zakoopi">

        <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
        <link type="text/css" rel="stylesheet" href="/materialadmin/assets/css/theme-default/bootstrap.css?1422792965" />
        <link type="text/css" rel="stylesheet" href="/materialadmin/assets/css/theme-default/materialadmin.css?1425466319" />
        <link type="text/css" rel="stylesheet" href="/materialadmin/assets/css/theme-default/font-awesome.min.css?1422529194" />
        <link type="text/css" rel="stylesheet" href="/materialadmin/assets/css/theme-default/material-design-iconic-font.min.css?1421434286" />

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="/materialadmin/assets/js/libs/utils/html5shiv.js?1403934957"></script>
        <script type="text/javascript" src="/materialadmin/assets/js/libs/utils/respond.min.js?1403934956"></script>
        <![endif]-->
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body class="menubar-hoverable header-fixed menubar-pin">
        <?= $this->fetch('content') ?>
        
    </body>
</html>
