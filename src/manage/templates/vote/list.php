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
                <h2 class="page-title">Data Survey</h2>
            </div>
            <div class="column w50 right-titlebar">
                <nav class="breadcrumb">
                    <ul>
                        <li class="is-active">
                            <span>Data Survey</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div><!-- .titlebar -->
        
        <div class="main-content">
            <form name="votes" action="/manage/votes/" method="POST">
                <div class="panel">
                    <header class="panel-header has-no-border">
                        <h3 class="panel-title">
                            Data Survey
                        </h3>
                    </header>
                    <div class="panel-content is-gapless">
        
                        <div class="table">
                            <header class="thead">
                                <div class="row">
                                    <div class="col w20">
                                        Tangal Survey
                                    </div>
                                    <div class="col w15">
                                        Nama Responden
                                    </div>
                                    <div class="col w10">
                                       Jenjang Usia
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
                                    <div class="col w5">
                                        Surveyor
                                    </div>
                                    <div class="col w10">
                                        &nbsp;
                                    </div>
                                </div>
                            </header>
                            <div class="tbody">
                                <?php foreach ($votes as $vote) : ?>
                                    <div class="row">
                                        <div class="col w20">
                                            <?=escHtml(Carbon::parse($vote->created_at)->translatedFormat('l, j F Y'))?>
                                            <div>
                                                <small>
                                                    Jam <?=escHtml(Carbon::parse($vote->created_at)->translatedFormat('H:i'))?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col w15">
                                            <?=ucwords(escHtml($vote->respondent->name))?>
                                        </div>
                                        <div class="col w10">
                                            <?=escHtml($vote->respondent->age_range)?>th
                                        </div>
                                        <div class="col w10">
                                            <?=escHtml($vote->respondent->education)?>
                                        </div>
                                        <div class="col w15">
                                            <?=escHtml($vote->respondent->job)?>
                                        </div>
                                        <div class="col w15">
                                            <?=ucwords(escHtml($vote->respondent->address))?>
                                        </div>
                                        <div class="col w5">
                                            <?=ucwords(escHtml($vote->surveyor->first_name))?>
                                        </div>
                                        <div class="col w10">
                                            <div class="buttons action-buttons">
                                                <a href="/manage/survey/<?=escHtml($surveyId)?>/votes/edit/<?=escHtml($vote->id)?>/" class="button is-small is-rounded">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <a href="/manage/survey/<?=escHtml($surveyId)?>/votes/delete/<?=escHtml($vote->id)?>/" class="button is-small is-rounded delete-button">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <footer class="tfoot">
                                <nav class="pagination is-rounded">
                                    <ul class="pagination-list">
                                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                            <li class="<?=($i === $currentPage ? 'is-active' : '')?>">
                                                <a href="/manage/survey/<?=$surveyId?>/votes/page/<?=$i?>" class="pagination-link">
                                                    <?=$i?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </footer>
                        </div>
        
                    </div>
        
                </div>
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div><!-- .right-layout -->

<?php require __DIR__ . '/../footer.php'; ?>