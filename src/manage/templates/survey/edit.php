<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Edit Survey</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="index.html">Survey</a>
                        </li>
                        <li class="is-active">
                            <span>Edit</span>
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
            <form name="add_survey" action="/manage/edit/" method="POST">
                <input type="hidden" name="id" value="<?=escHtmlAttr($survey['id'])?>">
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
                            Detail Survey
                        </h3>
                    </header>
                    <div class="panel-content is-slim is-light">

                        <div class="material field">
                            <div class="control">
                                <input type="text" id="title" name="title" value="<?=$survey['title']?>" class="input"
                                    placeholder="Judul Survey" autocomplete="off" required>
                                <hr>
                                <label for="title">Judul Survey</label>
                            </div>
                        </div>

                    </div><!-- .panel-content -->

                    <div class="panel-content">

                        <div class="material field">
                            <label for="is_active" class="label">
                                Status Survey
                            </label>
                            <label for="is_active" class="label checkbox-label">
                                Aktif
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="checkbox" <?=($survey['is_active'] ? 'checked' : '')?>>
                                <div class="indicator"></div>
                            </label>
                        </div>

                        <div class="material field w25">
                            <div class="control">
                                <input type="number" id="target" name="target" value="<?=$survey['target']?>" class="input"
                                    placeholder="Target Identifikasi" required>
                                <hr>
                                <label for="target">Target Identifikasi</label>
                            </div>
                        </div>

                        <div class="material fields has-2">
                            <div class="field">
                                <div class="control">
                                    <input type="text" name="start_date" id="start_date" value="<?=$survey['start_date']?>"
                                        class="input" placeholder="Tanggal Mulai" value=""
                                        autocomplete="off" required>
                                    <hr>
                                    <label for="start_date">Tanggal Mulai</label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="text" name="end_date" id="end_date" value="<?=$survey['end_date']?>"
                                        class="input" placeholder="Tanggal Selesai" value=""
                                        autocomplete="off" required>
                                    <hr>
                                    <label for="end_date">Tanggal Selesai</label>
                                </div>
                            </div>
                        </div>

                    </div><!-- .panel-content -->

                </div><!-- .panel -->
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>