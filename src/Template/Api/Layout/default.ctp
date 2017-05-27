<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->Html->charset() ?>
        <title>Business Zakoopi Api Doc List</title>
        <?= $this->Html->meta('icon') ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <div>
            <?= $this->Flash->render() ?>
        </div>
        <?= $this->fetch('content') ?>
    </body>
</html>
