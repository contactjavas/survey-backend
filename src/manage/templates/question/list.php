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
                <h2 class="page-title">Pertanyaan</h2>
            </div>
            <div class="column w50 right-titlebar">
                <nav class="breadcrumb">
                    <ul>
                        <li class="is-active">
                            <span>Pertanyaan</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div><!-- .titlebar -->
        
        <div class="main-content">
            <form name="candidate_list" action="" method="POST">
                <div class="panel">
                    <header class="panel-header has-no-border">
                        <h3 class="panel-title">
                            Daftar Pertanyaan
                        </h3>
                    </header>
                    <div class="panel-content is-gapless">
        
                        <div class="table">
                            <header class="thead">
                                <div class="row">
                                    <div class="col w50">
                                        Pertanyaan
                                    </div>
                                    <div class="col w40">
                                        Tipe Jawaban
                                    </div>
                                    <div class="col w10">
                                        &nbsp;
                                    </div>
                                </div>
                            </header>
                            <div class="tbody">
                                <?php foreach ($questions as $question) : ?>
                                    <div class="row">
                                        <div class="col w50">
                                            <?=escHtml($question->title)?>
                                        </div>
                                        <div class="col w40">
                                            <?=escHtml($question->type)?>
                                        </div>
                                        <div class="col w10">
                                            <div class="buttons action-buttons">
                                                <a href="/manage/survey/<?=escHtmlAttr($surveyId)?>/questions/edit/<?=escHtml($question->id)?>/" class="button is-small is-rounded">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <a href="/manage/survey/<?=escHtmlAttr($surveyId)?>/questions/delete/<?=escHtml($question->id)?>/" class="button is-small is-rounded delete-button">
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