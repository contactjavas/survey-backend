<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?=$baseUrl?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/usefulteam/columns@1.0.0/dist/columns.min.css">
    <link rel="stylesheet" href="/public/assets/slim-ui/assets/css/slim-ui.min.css">
</head>

<body spellcheck="false" class="login-page">

    <div id="app" class="login-app login-box">

        <div class="left-section form-section">
            <form action="" method="post" class="login-form material">
                <a href="" class="logo-link">
                    <img src="/public/assets/front/images/sample-logo-compact.png" alt="Logo" class="logo">
                </a>

                <h1>Welcome Back</h1>

                <?php if (isset($errorMessage)) : ?>
                    <div class="notification is-error">
                        <?=$errorMessage?>
                    </div>
                <?php endif; ?>

                <div class="field">
                    <div class="control">
                        <input type="email" name="email" id="email" placeholder="Masukkan email" class="input"
                            autocomplete="off" autofocus required>
                        <hr>
                        <label for="email">Email</label>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <input type="password" name="password" placeholder="Masukkan password" id="password"
                            class="input" autocomplete="off" required>
                        <hr>
                        <label for="password">Password</label>
                    </div>
                </div>

                <div class="field">
                    <label for="remember_me" class="label checkbox-label">
                        Keep me logged in
                        <input type="checkbox" name="remember_me" id="remember_me" value="1" class="checkbox" checked>
                        <div class="indicator"></div>
                    </label>
                </div>

                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-secondary is-full is-oval">Log in</button>
                    </div>
                </div>

            </form>
        </div>
        <div class="right-section illustration-section">

        </div>

    </div><!-- #app -->

    <script src="/public/assets/slim-ui/assets/js/slim-ui.min.js"></script>
</body>

</html>