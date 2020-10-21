<?php

use Carbon\Carbon;

require __DIR__ . '/header.php';

?>

<div class="left-layout">
    <?php require __DIR__ . '/sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="toolbar">
            <div class="left-toolbar">
                &nbsp;
            </div>
            <div class="right-toolbar">
                <div class="toolbar-item toolbar-avatar">
                    <img src="/public/assets/manage/images/sample-avatar.png" alt="avatar" class="avatar">
                    <span>
                        Hai <?=$currentUser->first_name?>
                    </span>
                </div><!-- .toolbar-item -->
            </div>
        </div>
    </div>
    <div class="main-screen">
        <div class="columns titlebar">
            <div class="column w50 left-titlebar">
                <h2 class="page-title">Pengguna</h2>
            </div>
            <div class="column w50 right-titlebar">
                <nav class="breadcrumb">
                    <ul>
                        <li class="is-active">
                            <span>Pengguna</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div><!-- .titlebar -->
        
        <div class="main-content">
            <form name="users" action="/manage/users/" method="POST">
                <div class="panel">
                    <header class="panel-header has-no-border">
                        <h3 class="panel-title">
                            Daftar Pengguna
                        </h3>
                    </header>
                    <div class="panel-content is-gapless">

                        <?php if ($debugType === 'check-respondents') : ?>
                            <div class="table">
                                <header class="thead">
                                    <div class="row">
                                        <div class="col w25">
                                            Nama Responden
                                        </div>
                                        <div class="col w25">
                                            Ditambahkan oleh
                                        </div>
                                        <div class="col w10">
                                            &nbsp;
                                        </div>
                                    </div>
                                </header>
                                <div class="tbody">
                                    <?php foreach ($votes as $vote_id => $data) : ?>
                                        <?php
                                        $surveyor   = $data['surveyor'];
                                        $respondent = $data['respondent'];
                                        $vote       = $data['vote'];
                                        ?>
                                        
                                        <div class="row">
                                            <div class="col w25">
                                                <?=escHtml($respondent->name)?>
                                            </div>
                                            <div class="col w25">
                                                <?=escHtml($surveyor->first_name)?> - <?=escHtml($surveyor->id)?>
                                                (<?=escHtml($respondent->added_by)?>)
                                            </div>
                                            <div class="col w10">
                                                <div class="buttons action-buttons">
                                                    <a href="/manage/users/edit/" class="button is-small is-rounded">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <a href="/manage/users/delete/" class="button is-small is-rounded delete-button">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    <?php endforeach; ?>
                                </div>
                                <footer class="tfoot">
                                    &nbsp;
                                </footer>
                            </div>

                        <?php endif; ?>
        
                    </div>
        
                </div>
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div><!-- .right-layout -->

<?php require __DIR__ . '/footer.php'; ?>