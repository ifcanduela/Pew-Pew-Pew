<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <base href="<?= url() ?>">
    <title><?= $title = $this->title() ?> | <?= pew('app_title') ?></title>

    <link rel="stylesheet" href="<?= url('css/default.css') ?>">
    <script src="<?= url('js/jquery-3.1.0.min.js') ?>"></script>
    <script src="<?= url('js/knockout-3.4.0.min.js') ?>"></script>
</head>

<body>
    <div class="push">
        <header>
            <div class="menu">
                <a href="<?= url() ?>">Home</a>
                | <a href="<?= url('welcome') ?>">Welcome</a>
            </div>

            <h1><a href="<?= url() ?>"><?= pew('app_title') ?></a></h1>
        </header>

        <div id="main">
            <?php foreach (flash() as $key => $value): ?>
                <p class="alert <?= $key ?>">
                    <?= $value ?>
                </p>
            <?php endforeach ?>

            <?= $this->child() ?>
        </div>
    </div>

    <footer class="sticky">
        <p>Powered by <a href="https://github.com/ifcanduela/pew">Pew-Pew-Pew</a></p>
    </footer>

    <script src="<?= url('js/app.js') ?>"></script>

    <?= $this->block('scripts') ?>
</body>
</html>
