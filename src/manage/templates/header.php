<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Survey App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?=$baseUrl?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/awesomplete@1.1.5/awesomplete.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css">
    <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-grid.css">
    <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-theme-alpine.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/usefulteam/columns@1.0.0/dist/columns.min.css">

    <?php if (isset($css) && isset($css['libs'])) : ?>
        <?php foreach ($css['libs'] as $url) : ?>
            <link rel="stylesheet" href="<?=escHtml($url)?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <link rel="stylesheet" href="/public/assets/slim-ui/assets/css/slim-ui.min.css">

    <?php if (isset($css) && isset($css['styles'])) : ?>
        <?php foreach ($css['styles'] as $url) : ?>
            <link rel="stylesheet" href="<?=escHtml($url)?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        var baseUrl = '<?=escHtml($baseUrl)?>';
    </script>
</head>

<body spellcheck="false" class="use-sidebar">

    <div id="app">