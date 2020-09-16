<?php

declare(strict_types=1);

?>

        <footer class="page-footer">
            <div class="container">
                <ul class="footer-menu">
                    <li>
                        <a href="">Settings</a>
                    </li>
                    <li>
                        <a href="">Bantuan</a>
                    </li>
                </ul>
            </div><!-- .container -->
        </footer><!-- .page-footer -->
    
    </div><!-- #app -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tingle.js@0.15.3/dist/tingle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/awesomplete@1.1.5/awesomplete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
    <script src="https://unpkg.com/ag-grid-community@23.1.0/dist/ag-grid-community.min.noStyle.js"></script>
    <script src="/public/assets/slim-ui/assets/js/image-browser.js"></script>

    <?php if (isset($js) && isset($js['libs'])) : ?>
        <?php foreach ($js['libs'] as $url) : ?>
            <script src="<?=escHtml($url)?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="/public/assets/slim-ui/assets/js/slim-ui.min.js"></script>

    <?php if (isset($js) && isset($js['scripts'])) : ?>
        <?php foreach ($js['scripts'] as $url) : ?>
            <script src="<?=escHtml($url)?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>