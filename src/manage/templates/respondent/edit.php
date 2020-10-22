<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Ubah Responden</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="/manage/respondents/">Responden</a>
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
            <form name="edit_respondent" action="/manage/respondents/edit/<?=$respondent->id?>/" method="POST">
                <input type="hidden" name="id" value="<?=escHtmlAttr($respondent->id)?>">
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
                                            placeholder="Nama Lengkap" value="<?=(isset($respondent->name) ? $respondent->name : '')?>" autocomplete="off" required>
                                        <hr>
                                        <label for="name">Nama Lengkap</label>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <select name="gender_id" id="gender_id" class="input select is-filled" required="">
                                                <?php foreach ($genders as $gender) : ?>
                                                    <option value="<?=escHtmlAttr($gender->id)?>" <?=(isset($respondent->gender_id) && $gender->id == $respondent->gender_id ? 'selected' : '')?>>
                                                        <?=escHtml($gender->name)?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="gender_id">Jenis Kelamin</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <select name="age_range" id="age_range" class="input select is-filled" required="">
                                                <option value="17 - 22" <?=(isset($respondent->age_range) && '17 - 22' == $respondent->age_range ? 'selected' : '')?>>( 17 - 22 )</option>
                                                <option value="23 - 35" <?=(isset($respondent->age_range) && '23 - 35' == $respondent->age_range ? 'selected' : '')?>>( 23 - 35 )</option>
                                                <option value="36 - 50" <?=(isset($respondent->age_range) && '36 - 50' == $respondent->age_range ? 'selected' : '')?>>( 36 - 50 )</option>
                                                <option value=">50" <?=(isset($respondent->age_range) && '>50' == $respondent->age_range ? 'selected' : '')?>>( >50 )</option>
                                            </select>
                                            <label for="age_range">Usia</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <select name="religion_id" id="religion_id" class="input select is-filled" required="">
                                                <?php foreach ($religions as $religion) : ?>
                                                    <option value="<?=escHtmlAttr($religion->id)?>" <?=(isset($respondent->religion_id) && $religion->id == $respondent->religion_id ? 'selected' : '')?>>
                                                        <?=escHtml($religion->name)?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="religion_id">Agama</label>
                                        </div>
                                    </div>
                                
                                    <div class="field">
                                        <div class="control">
                                            <select name="education_id" id="education_id" class="input select is-filled" required="">
                                                <?php foreach ($educations as $education) : ?>
                                                    <option value="<?=escHtmlAttr($education->id)?>" <?=(isset($respondent->education_id) && $education->id == $respondent->education_id ? 'selected' : '')?>>
                                                        <?=escHtml($education->name)?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="education_id">Pendidikan Terakhir</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <select name="job" id="job" class="input select is-filled" required="">
                                                <option value="ASN" <?=(isset($respondent->job) && 'ASN' == $respondent->job ? 'selected' : '')?>>ASN</option>
                                                <option value="Wiraswasta" <?=(isset($respondent->job) && 'Wiraswasta' == $respondent->job ? 'selected' : '')?>>Wiraswasta</option>
                                                <option value="Pegawai" <?=(isset($respondent->job) && 'Pegawai' == $respondent->job ? 'selected' : '')?>>Pegawai</option>
                                                <option value="Pelajar/ Mahasiswa" <?=(isset($respondent->job) && 'Pelajar/ Mahasiswa' == $respondent->job ? 'selected' : '')?>>Pelajar/ Mahasiswa</option>
                                                <option value="Lain- lain" <?=(isset($respondent->job) && 'Lain- lain' == $respondent->job ? 'selected' : '')?>>Lain- lain</option>
                                            </select>
                                            <label for="job">Pekerjaan</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <select name="income_range" id="income_range" class="input select is-filled" required="">
                                                <option value="Rp. 0 - Rp. 1.500.000 ,00" <?=(isset($respondent->income_range) && 'Rp. 0 - Rp. 1.500.000 ,00' == $respondent->income_range ? 'selected' : '')?>>Rp. 0 - Rp. 1.500.000 ,00</option>
                                                <option value="Rp. 1.500.000 ,00 - Rp. 3.000.000 ,00" <?=(isset($respondent->income_range) && 'Rp. 1.500.000 ,00 - Rp. 3.000.000 ,00' == $respondent->income_range ? 'selected' : '')?>>Rp. 1.500.000 ,00 - Rp. 3.000.000 ,00</option>
                                                <option value="Rp. 3.000.000 ,00 - Rp. 6.000.000 ,00" <?=(isset($respondent->income_range) && 'Rp. 3.000.000 ,00 - Rp. 6.000.000 ,00' == $respondent->income_range ? 'selected' : '')?>>Rp. 3.000.000 ,00 - Rp. 6.000.000 ,00</option>
                                                <option value="> Rp. 6.000.000 ,00" <?=(isset($respondent->income_range) && '> Rp. 6.000.000 ,00' == $respondent->income_range ? 'selected' : '')?>> > Rp. 6.000.000 ,00</option>
                                            </select>
                                            <label for="income_range">Penghasilan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material field">
                                    <label for="">Aktif di media sosial</label>
                                    <label for="active_on_social_media_1" class="label radio-label">
                                        Ya
                                        <input type="radio" name="active_on_social_media" id="active_on_social_media_1" value="1" class="radio" <?=($respondent->active_on_social_media ? 'checked' : '')?>>
                                        <div class="indicator"></div>
                                    </label>

                                    <label for="active_on_social_media_0" class="label radio-label">
                                        Tidak
                                        <input type="radio" name="active_on_social_media" id="active_on_social_media_0" value="0" class="radio" <?=(!$respondent->active_on_social_media ? 'checked' : '')?>>
                                        <div class="indicator"></div>
                                    </label>
                                </div>

                                <div class="material field">
                                    <div class="control">
                                        <textarea name="address" id="address" class="input textarea" placeholder="Alamat Lengkap" autocomplete="off" required><?=(isset($respondent->address) ? $respondent->address : '')?></textarea>
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
                                            value="<?=(isset($respondent->photo) ? $respondent->photo : '')?>"
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