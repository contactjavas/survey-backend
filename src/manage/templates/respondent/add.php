<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Tambah Responden</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="/manage/respondents/">Responden</a>
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
            <form name="add_respondent" action="/manage/respondents/add/" method="POST">
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
                        <div class="panel">
                            <header class="panel-header">
                                <h3 class="panel-title">
                                    Profil Responden
                                </h3>
                            </header>

                            <div class="panel-content">

                                <div class="material field">
                                    <div class="control">
                                        <input type="text" name="name" id="name" class="input"
                                            placeholder="Nama Lengkap" value="<?=(isset($fields['name']) ? $fields['name'] : '')?>" autocomplete="off" required>
                                        <hr>
                                        <label for="name">Nama Lengkap</label>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <input type="number" name="age" id="age" class="input"
                                                placeholder="Usia" value="<?=(isset($fields['age']) ? $fields['age'] : '')?>" autocomplete="off" required>
                                            <hr>
                                            <label for="age">Usia</label>
                                        </div>
                                    </div>
                                
                                    <div class="field">
                                        <div class="control">
                                            <select name="gender_id" id="gender_id" class="input select is-filled" required="">
                                                <?php foreach ($genders as $gender) : ?>
                                                    <option value="<?=escHtmlAttr($gender->id)?>" <?=(isset($fields['gender_id']) && $gender->id == $fields['gender_id'] ? 'selected' : '')?>>
                                                        <?=escHtml($gender->name)?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="gender_id">Jenis Kelamin</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <select name="religion_id" id="religion_id" class="input select is-filled" required="">
                                                <?php foreach ($religions as $religion) : ?>
                                                    <option value="<?=escHtmlAttr($religion->id)?>" <?=(isset($fields['religion_id']) && $religion->id == $fields['religion_id'] ? 'selected' : '')?>>
                                                        <?=escHtml($religion->name)?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="religion_id">Agama</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="phone" id="phone" class="input"
                                                placeholder="Nomor Telepon" value="<?=(isset($fields['phone']) ? $fields['phone'] : '')?>" autocomplete="off" required>
                                            <hr>
                                            <label for="phone">Nomor Telepon</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <select name="education_id" id="education_id" class="input select is-filled" required="">
                                                <?php foreach ($educations as $education) : ?>
                                                    <option value="<?=escHtmlAttr($education->id)?>" <?=(isset($fields['education_id']) && $education->id == $fields['education_id'] ? 'selected' : '')?>>
                                                        <?=escHtml($education->name)?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="education_id">Pendidikan Terakhir</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="job" id="job" class="input"
                                                placeholder="Pekerjaan" value="<?=(isset($fields['job']) ? $fields['job'] : '')?>" autocomplete="off" required>
                                            <hr>
                                            <label for="job">Pekerjaan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="nik" id="nik"
                                                class="input" placeholder="NIK" value="<?=(isset($fields['nik']) ? $fields['nik'] : '')?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="nik">NIK</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="kk" id="kk"
                                                class="input" placeholder="Nomor KK" value="<?=(isset($fields['kk']) ? $fields['kk'] : '')?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="kk">Nomor KK</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material field">
                                    <div class="control">
                                        <textarea name="address" id="address" class="input textarea" placeholder="Alamat Lengkap" autocomplete="off" required><?=(isset($fields['address']) ? $fields['address'] : '')?></textarea>
                                        <hr>
                                        <label for="address">Alamat</label>
                                    </div>
                                </div>

                            </div><!-- .panel-content -->

                        </div><!-- .panel -->
                    </div>
                    <div class="side-edit-area">
                        <div class="panel">
                            <header class="panel-header">
                                <h3 class="panel-title">
                                    Foto Responden
                                </h3>
                            </header>

                            <div class="panel-content">
                                <div class="material field">
                                    <div class="control">
                                        <input
                                            type="text"
                                            class="input use-image-browser"
                                            name="photo"
                                            id="image-browser"
                                            placeholder="Upload foto responden"
                                        >
                                        <hr>
                                        <label for="address">Upload foto responden</label>
                                    </div>
                                </div>
                            </div><!-- .panel-content -->
                        </div>
                    </div>
                </div><!-- .edit-area -->
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>