<?php

use Carbon\Carbon;

require __DIR__ . '/../header.php';

?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
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
                <h2 class="page-title">Responden</h2>
            </div>
            <div class="column w50 right-titlebar">
                <nav class="breadcrumb">
                    <ul>
                        <li class="is-active">
                            <span>Responden</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div><!-- .titlebar -->
        
        <div class="main-content">
            <form name="respondents" action="/manage/respondents/" method="POST">
                <div class="panel">
                    <header class="panel-header has-no-border">
                        <h3 class="panel-title">
                            Daftar Responden
                        </h3>
                    </header>
                    <div class="panel-content is-gapless">
        
                        <div class="table">
                            <header class="thead">
                                <div class="row">
                                    <div class="col w20">
                                        Nama
                                    </div>
                                    <div class="col w10">
                                        Jenis Kelamin
                                    </div>
                                    <div class="col w10">
                                        Usia
                                    </div>
                                    <div class="col w10">
                                        Agama
                                    </div>
                                    <div class="col w10">
                                        Pendidikan
                                    </div>
                                    <div class="col w15">
                                        Pekerjaan
                                    </div>
                                    <div class="col w15">
                                        Alamat
                                    </div>
                                    <div class="col w10">
                                        &nbsp;
                                    </div>
                                </div>
                            </header>
                            <div class="tbody">
                                <?php foreach ($respondents as $respondent) : ?>
                                    <div class="row">
                                        <div class="col w20">
                                            <?=ucwords(escHtml($respondent->name))?>
                                        </div>
                                        <div class="col w10">
                                            <?=escHtml($respondent->gender)?>
                                        </div>
                                        <div class="col w10">
                                            <?=escHtml($respondent->age_range)?>th
                                        </div>
                                        <div class="col w10">
                                            <?=escHtml($respondent->religion)?>
                                        </div>
                                        <div class="col w10">
                                            <?=escHtml($respondent->education)?>
                                        </div>
                                        <div class="col w15">
                                            <?=escHtml($respondent->job)?>
                                        </div>
                                        <div class="col w15">
                                            <?=ucwords(escHtml($respondent->address))?>
                                        </div>
                                        <div class="col w10">
                                            <div class="buttons action-buttons">
                                                <a href="/manage/respondents/edit/<?=escHtml($respondent->id)?>/" class="button is-small is-rounded">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <a href="/manage/respondents/delete/<?=escHtml($respondent->id)?>/" class="button is-small is-rounded delete-button">
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
        
                    </div>
        
                </div>
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div><!-- .right-layout -->

<?php require __DIR__ . '/../footer.php'; ?>