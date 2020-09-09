<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Tambah Calon</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="/manage/<?=escHtmlAttr($surveyId)?>/candidates/">Calon</a>
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
            <form name="add_respondent" action="/manage/survey/<?=escHtmlAttr($surveyId)?>/candidates/edit/<?=escHtmlAttr($candidate->id)?>/" method="POST">
                <input type="hidden" name="survey_id" value="<?=escHtmlAttr($surveyId)?>">
                <input type="hidden" name="candidate_id" value="<?=escHtmlAttr($candidate->id)?>">
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
                            Data Calon
                        </h3>
                    </header>

                    <div class="panel-content">

                        <div class="material field">
                            <div class="control">
                                <input type="text" name="name" id="name" class="input"
                                    placeholder="Nama Calon" value="<?=(isset($candidate->name) ? $candidate->name : '')?>" autocomplete="off" required>
                                <hr>
                                <label for="name">Nama Calon</label>
                            </div>
                        </div>

                        <div class="material field">
                            <div class="control">
                                <select name="candidate_type_id" id="candidate_type_id" class="input select is-filled" required="">
                                    <?php foreach ($candidateTypes as $candidateType) : ?>
                                        <option value="<?=escHtmlAttr($candidateType->id)?>" <?=(isset($candidate->candidate_type_id) && $candidateType->id == $candidate->candidate_type_id ? 'selected' : '')?>>
                                            <?=escHtml($candidateType->name)?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="candidate_type_id">Status Pencalonan</label>
                            </div>
                        </div>

                    </div><!-- .panel-content -->

                </div><!-- .panel -->
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>