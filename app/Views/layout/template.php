<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Cyklistické závody') ?></title>
    <?= $this->include('layout/assets') ?>
</head>
<body>
<div class="container">
    <div class="pt-3"><?= $this->include('layout/alert') ?></div>
    <?= $this->renderSection('content') ?>
</div>
</body>
</html>
