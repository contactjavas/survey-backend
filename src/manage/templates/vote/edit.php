<?php

use App\Shared\Models\QuestionChoice;

?>

<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Input Survey</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="/manage/<?=escHtmlAttr($surveyId)?>/votes/">Data Survey</a>
                        </li>
                        <li class="is-active">
                            <span>Ubah</span>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="right-action-bar">
                <button class="button is-secondary save-button">Update</button>
            </div>
        </div>
    </div>
    <div class="main-screen">
        <div class="main-content">
            <form name="edit_vote" action="/manage/survey/<?=escHtmlAttr($surveyId)?>/votes/edit/<?=escHtmlAttr($vote->id)?>/" method="POST">
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

                <div class="edit-area">
                    <div class="main-edit-area">
                        <?php foreach ($questions as $questionIndex => $question) : ?>
                            <div class="panel">
                                <header class="panel-header">
                                    <h3 class="panel-title">
                                        Pertanyaan #<?=($questionIndex + 1)?>
                                    </h3>
                                </header>

                                <div class="panel-content">
                                    <div class="panel-info">
                                        <h4 class="info-title"><?=escHtml($question->type)?></h4>
                                        <p><?=escHtml($question->title)?></p>
                                    </div>

                                    <?php if ($question->question_type_id == 1 || $question->question_type_id == 2) : ?>
                                        <?php
                                        $allow_multiple = $question->question_type_id == 1 ? false : true;
                                        $choices        = QuestionChoice::where('question_id', $question->id)->get();
                                        ?>

                                        <?php foreach ($choices as $choiceIndex => $choice) : ?>
                                            <?php if ($allow_multiple) : ?>
                                                <div class="material field">
                                                    <label for="question_choices_<?=escHtmlAttr($question->id)?>[]_<?=$choiceIndex?>" class="label checkbox-label">
                                                        <?=escHtmlAttr($choice->title)?>
                                                        <input type="checkbox" name="question_choices_<?=escHtmlAttr($question->id)?>[]" id="question_choices_<?=escHtmlAttr($question->id)?>[]_<?=$choiceIndex?>" value="<?=escHtmlAttr($choice->id)?>" class="checkbox" <?=(in_array($choice->id, $answers[$question->id]) ? 'checked' : '')?>>
                                                        <div class="indicator"></div>
                                                    </label>
                                                </div>
                                            <?php else : ?>
                                                <div class="material field">
                                                    <label for="question_choice_<?=escHtmlAttr($question->id)?>_<?=$choiceIndex?>" class="label radio-label">
                                                        <?=escHtmlAttr($choice->title)?>
                                                        <input type="radio" name="question_choice_<?=escHtmlAttr($question->id)?>" id="question_choice_<?=escHtmlAttr($question->id)?>_<?=$choiceIndex?>" value="<?=escHtmlAttr($choice->id)?>" class="radio" <?=($choice->id == $answers[$question->id] ? 'checked' : '')?>>
                                                        <div class="indicator"></div>
                                                    </label>
                                                </div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    <?php else : ?>
                                        <div class="material field">
                                            <div class="control">
                                                <textarea name="question_answer_<?=escHtmlAttr($question->id)?>" id="question_answer_<?=escHtmlAttr($question->id)?>" class="input textarea" placeholder="Masukkan Jawaban" autocomplete="off" required><?=(isset($answers[$question->id]) ? $answers[$question->id] : '')?></textarea>
                                                <hr>
                                                <label for="question_answer_<?=escHtmlAttr($question->id)?>">Jawaban</label>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                </div><!-- .panel-content -->

                            </div><!-- .panel -->

                        <?php endforeach; ?>
                    </div>
                    <div class="side-edit-area">
                        <div class="panel">
                            <header class="panel-header">
                                <h3 class="panel-title">
                                    Data Survey
                                </h3>
                            </header>

                            <div class="panel-content">

                                <div class="material field">
                                    <input type="hidden" name="respondent_id" id="respondent_id" value="<?=escHtmlAttr($vote->respondent_id)?>" required>
                                    <div class="control">
                                        <input type="text" name="respondent_search" id="respondent_search" value="<?=escHtmlAttr($respondent->name . ' (' . $respondent->nik . ')')?>"
                                        class="input use-autocomplete is-filled" placeholder="Nama / NIK Responden" autocomplete="off"
                                        data-ajax-url="<?=$baseUrl?>/api/awesomplete/respondent/search/{query}/"
                                        data-use-array-of="object" data-store-value-to="respondent_id" data-minchars="1">
                                        <hr>
                                        <label for="respondent_search">Nama / NIK Responden</label>
                                    </div>
                                </div>

                                <div class="material field">
                                    <input type="hidden" name="user_id" id="user_id" value="<?=escHtmlAttr($vote->user_id)?>" required>
                                    <div class="control">
                                        <input type="text" name="surveyor_search" id="surveyor_search" value="<?=escHtmlAttr($surveyor->first_name . ' ' . $surveyor->last_name)?>"
                                        class="input use-autocomplete is-filled" placeholder="Nama Surveyor" autocomplete="off"
                                        data-ajax-url="<?=$baseUrl?>/api/awesomplete/surveyor/search/{query}/"
                                        data-use-array-of="object" data-store-value-to="user_id" data-minchars="1">
                                        <hr>
                                        <label for="surveyor_search">Nama Surveyor</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>