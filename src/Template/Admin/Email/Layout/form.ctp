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

        <?= $this->element('formcss')?>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body class="menubar-hoverable header-fixed menubar-pin">
        <?= $this->fetch('content') ?>
        
    </body>
</html>
