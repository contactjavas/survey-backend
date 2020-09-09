<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Tambah Pertanyaan</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="/manage/<?=escHtmlAttr($surveyId)?>/questions/">Pertanyaan</a>
                        </li>
                        <li class="is-active">
                            <span>Tambah</span>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="right-action-bar">
                <button class="button is-secondary save-button">Simpan</button>
            </div>
        </div>
    </div>
    <div class="main-screen">
        <div class="main-content">
            <form name="add_respondent" action="/manage/survey/<?=escHtmlAttr($surveyId)?>/questions/add/" method="POST">
                <input type="hidden" name="survey_id" value="<?=escHtmlAttr($surveyId)?>">
                <?php if (isset($successMessage)) : ?>
                    <div class="notification is-success">
                        <?=$successMessage?>
                    </div>
                <?php endif; ?>
                <?php if (isset($errorMessage)) : ?>
                    <div class="notification is-error">
                        <?=$errorMessage?>
                    </div>
                <?php endif; ?>
                <div class="panel">
                    <header class="panel-header">
                        <h3 class="panel-title">
                            Data Pertanyaan
                        </h3>
                    </header>

                    <div class="panel-content">

                        <div class="material field">
                            <div class="control">
                                <input type="text" name="title" id="title" class="input"
                                    placeholder="Pertanyaan" value="<?=(isset($fields['title']) ? $fields['title'] : '')?>" autocomplete="off" required>
                                <hr>
                                <label for="title">Pertanyaan</label>
                            </div>
                        </div>

                        <div class="material field">
                            <div class="control">
                                <select name="question_type_id" id="question_type_id" class="input select is-filled" required="">
                                    <?php foreach ($questionTypes as $questionType) : ?>
                                        <option value="<?=escHtmlAttr($questionType->id)?>" <?=(isset($fields['question_type_id']) && $questionType->id == $fields['question_type_id'] ? 'selected' : '')?>>
                                            <?=escHtml($questionType->name)?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="question_type_id">Type Jawaban</label>
                            </div>
                        </div>

                        <div class="material field add-choice-field">
                            <label for="allow_add" class="label">
                                Menambah Pilihan
                            </label>
                            <label for="allow_add" class="label checkbox-label">
                                Izinkan responden menambahkan sendiri pilihannya.
                                <input type="checkbox" name="allow_add" id="allow_add" value="1" class="checkbox" checked>
                                <div class="indicator"></div>
                            </label>
                        </div>

                    </div><!-- .panel-content -->

                </div><!-- .panel -->
                
                <div class="panel question-choices-panel">
                    <header class="panel-header">
                        <h3 class="panel-title">
                            Pilihan Jawaban
                        </h3>
                    </header>

                    <div class="panel-content">
                        <?php
                        $timestamp = time();
                        ?>

                        <div class="repeater-fields choice-fields">
                            <?php if (isset($questionChoices)) : ?>

                                <?php foreach ($questionChoices as $questionChoice) : ?>

                                    <div class="material field choice-field">
                                    <div class="control">
                                        <input type="text" name="<?=$questionChoice?>" id="<?=$questionChoice?>" class="input"
                                            placeholder="Masukkan Pilihan" value="<?=(isset($fields[$questionChoice]) ? $fields[$questionChoice] : '')?>" autocomplete="off" required>
                                        <hr>
                                        <label for="<?=$questionChoice?>">Pilihan</label>
                                    </div>
                                    <button type="button" class="button minus-button is-small is-rounded is-secondary is-light">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>

                                <?php endforeach; ?>

                            <?php else : ?>

                                <div class="material field choice-field">
                                    <div class="control">
                                        <input type="text" name="new_question_choice_<?=$timestamp?>" id="new_question_choice_<?=$timestamp?>" class="input"
                                            placeholder="Masukkan Pilihan" value="<?=(isset($fields['title']) ? $fields['title'] : '')?>" autocomplete="off" required>
                                        <hr>
                                        <label for="new_question_choice_<?=$timestamp?>">Pilihan</label>
                                    </div>
                                    <button type="button" class="button minus-button is-small is-rounded is-secondary is-light">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>

                            <?php endif; ?>
                        </div>


                        <div class="plus-button-area">
                            <button type="button" class="button plus-button is-rounded is-secondary is-light">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                    </div><!-- .panel-content -->

                </div><!-- .panel -->
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>