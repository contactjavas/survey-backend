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
                                            <input type="number" name="age" id="age" class="input"
                                                placeholder="Usia" value="<?=(isset($respondent->age) ? $respondent->age : '')?>" autocomplete="off" required>
                                            <hr>
                                            <label for="age">Usia</label>
                                        </div>
                                    </div>

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
                                            <input type="text" name="phone" id="phone" class="input"
                                                placeholder="Nomor Telepon" value="<?=(isset($respondent->phone) ? $respondent->phone : '')?>" autocomplete="off" required>
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
                                                    <option value="<?=escHtmlAttr($education->id)?>" <?=(isset($respondent->education_id) && $education->id == $respondent->education_id ? 'selected' : '')?>>
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
                                                placeholder="Pekerjaan" value="<?=(isset($respondent->job) ? $respondent->job : '')?>" autocomplete="off" required>
                                            <hr>
                                            <label for="job">Pekerjaan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="nik" id="nik"
                                                class="input" placeholder="NIK" value="<?=(isset($respondent->nik) ? $respondent->nik : '')?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="nik">NIK</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="kk" id="kk"
                                                class="input" placeholder="Nomor KK" value="<?=(isset($respondent->kk) ? $respondent->kk : '')?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="kk">Nomor KK</label>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- .panel-content -->

                        </div><!-- .panel -->
                    </div>
                    <div class="side-edit-area">
                        <div class="panel">
                            <header class="panel-header">
                                <h3 class="panel-title">
                                    Alamat Responden
                                </h3>
                            </header>

                            <div class="panel-content">
                                <div class="material field">
                                    <div class="control">
                                        <textarea name="address" id="address" class="input textarea" placeholder="Alamat Lengkap" autocomplete="off" required><?=(isset($respondent->address) ? $respondent->address : '')?></textarea>
                                        <hr>
                                        <label for="address">Alamat</label>
                                    </div>
                                </div>

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="rt" id="rt"
                                                class="input" placeholder="RT" value="<?=(isset($respondent->rt) ? $respondent->rt : '')?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="rt">RT</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="rw" id="rw"
                                                class="input" placeholder="RW" value="<?=(isset($respondent->rw) ? $respondent->rw : '')?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="rw">RW</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material field">
                                    <label class="label select2-label" for="province_id">Provinsi</label>
                                    <div class="control">
                                        <select name="province_id" id="province_id" class="input select is-filled" required="">
                                            <?php foreach ($provinces as $province) : ?>
                                                <option value="<?=escHtmlAttr($province->id)?>" <?=($province->id == $respondent->province_id ? 'selected' : '')?>>
                                                    <?=escHtml($province->name)?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="material field">
                                    <label class="label select2-label" for="regency_id">Kabupaten</label>
                                    <div class="control">
                                        <select name="regency_id" id="regency_id" class="input select is-filled" required="">
                                            <?php foreach ($regencies as $regency) : ?>
                                                <option value="<?=escHtmlAttr($regency->id)?>" <?=($regency->id == $respondent->regency_id ? 'selected' : '')?>>
                                                    <?=escHtml($regency->name)?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="material field">
                                    <label class="label select2-label" for="district_id">Kecamatan</label>
                                    <div class="control">
                                        <select name="district_id" id="district_id" class="input select is-filled" required="">
                                            <?php foreach ($districts as $district) : ?>
                                                <option value="<?=escHtmlAttr($district->id)?>" <?=($district->id == $respondent->district_id ? 'selected' : '')?>>
                                                    <?=escHtml($district->name)?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="material field">
                                    <label class="label select2-label" for="village_id">Desa / Kelurahan</label>
                                    <div class="control">
                                        <select name="village_id" id="village_id" class="input select is-filled" required="">
                                            <?php foreach ($villages as $village) : ?>
                                                <option value="<?=escHtmlAttr($village->id)?>" <?=($village->id == $respondent->village_id ? 'selected' : '')?>>
                                                    <?=escHtml($village->name)?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div><!-- .edit-area -->
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>