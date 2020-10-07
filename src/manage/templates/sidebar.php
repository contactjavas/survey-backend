<?php

declare(strict_types=1);

$menu = require __DIR__ . '/menu.php';
$menu = $menu();
?>

<div class="sidebar" data-simplebar>
    <div class="logo-area">
        <a href="" class="logo-link">
            <img src="/public/assets/manage/images/votty-logo.png" alt="Logo" class="logo">
        </a>
    </div>

    <nav class="sidenav">
        <?php
        foreach ($menu as $menuCategory => $menuList) {
            $categoryText = str_ireplace('_', ' ', $menuCategory);
            $categoryText = ucwords($categoryText);
            ?>
            <h3 class="sidemenu-title"><?=$categoryText?></h3>
            <ul class="sidemenu">
                <?php
                foreach ($menuList as $menuIndex => $menuItem) {
                    $activeMenuClass = $menuItem['url'] === $activeMenu ? 'is-active' : '';
                    ?>
                    <li class="<?=($menuItem['submenu'] ? 'has-submenu' : '')?> <?=$activeMenuClass?>">
                        <a href="<?=$menuItem['url']?>">
                            <span class="icon">
                                <i class="<?=$menuItem['icon']?>"></i>
                            </span>
                            <?=$menuItem['text']?>
                            <?php if ($menuItem['submenu']) : ?>
                                <span class="arrow">
                                    <i class="fas fa-angle-down"></i>
                                </span>
                            <?php endif; ?>
                        </a>
                        <?php if ($menuItem['submenu']) : ?>
                            <ul class="submenu">
                                <?php
                                foreach ($menuItem['submenu'] as $submenuIndex => $submenuItem) {
                                    $activeSubmenuClass = $submenuItem['url'] === $activeSubmenu ? 'is-active' : '';
                                    ?>
                                    <li class="<?=$activeSubmenuClass?>">
                                        <a href="<?=$submenuItem['url']?>">
                                            <?=$submenuItem['text']?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
        }
        ?>
    </nav>
</div>