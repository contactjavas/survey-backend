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
                <h2 class="page-title">Survey</h2>
            </div>
            <div class="column w50 right-titlebar">
                <nav class="breadcrumb">
                    <ul>
                        <li class="is-active">
                            <span>Survey</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div><!-- .titlebar -->
        
        <div class="main-content">
            <form name="survey_list" action="" method="POST">
                <div class="panel">
                    <header class="panel-header has-no-border">
                        <h3 class="panel-title">
                            Daftar Survey
                        </h3>
                    </header>
                    <div class="panel-content is-gapless">
        
                        <div class="table">
                            <header class="thead">
                                <div class="row">
                                    <div class="col w5">
                                        Aktif
                                    </div>
                                    <div class="col w35">
                                        Judul Survey
                                    </div>
                                    <div class="col w10">
                                        Target Identifikasi
                                    </div>
                                    <div class="col w10">
                                        Progress
                                    </div>
                                    <div class="col w15">
                                        Tanggal Mulai
                                    </div>
                                    <div class="col w15">
                                        Tanggal Selesai
                                    </div>
                                    <div class="col w10">
                                        &nbsp;
                                    </div>
                                </div>
                            </header>
                            <div class="tbody">
                                <?php foreach ($surveys as $survey) : ?>
                                    <div class="row">
                                        <div class="col w5 status <?=($survey->is_active ? 'is-active' : '')?>">
                                            <i class="<?=($survey->is_active ? 'fas fa-check' : 'fas fa-minus')?>"></i>
                                        </div>
                                        <div class="col w35">
                                            <?=escHtml($survey->title)?>
                                        </div>
                                        <div class="col w10">
                                            <?=escHtml($survey->target)?>
                                        </div>
                                        <div class="col w10">
                                            0%
                                        </div>
                                        <div class="col w15">
                                            <?php
                                            $startDate = Carbon::parse($survey->start_date)->translatedFormat('j F Y');
                                            ?>
                                            
                                            <?=escHtml($startDate)?>
                                        </div>
                                        <div class="col w15">
                                            <?php
                                            $endDate = Carbon::parse($survey->end_date)->translatedFormat('j F Y');
                                            ?>
                                            
                                            <?=escHtml($endDate)?>
                                        </div>
                                        <div class="col w10">
                                            <div class="buttons action-buttons">
                                                <a href="/manage/edit/<?=escHtml($survey->id)?>/" class="button is-small is-rounded">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <a href="/manage/delete/<?=escHtml($survey->id)?>/" class="button is-small is-rounded delete-button">
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