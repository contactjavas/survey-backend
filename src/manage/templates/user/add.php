<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Tambah Pengguna</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="<?=escHtmlAttr($activeMenu)?>">Pengguna</a>
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
            <form name="add_survey" action="/manage/users/add/" method="POST">
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
                            Profil Pengguna
                        </h3>
                    </header>

                    <div class="panel-content">

                        <div class="material fields has-2">
                            <div class="field">
                                <div class="control">
                                    <input type="text" name="first_name" id="first_name" class="input"
                                        placeholder="Nama Depan" value="<?=(isset($fields['first_name']) ? $fields['first_name'] : '')?>" autocomplete="off" required>
                                    <hr>
                                    <label for="first_name">Nama Depan</label>
                                </div>
                            </div>
                        
                            <div class="field">
                                <div class="control">
                                    <input type="text" name="last_name" id="last_name" class="input"
                                        placeholder="Nama Belakang" value="<?=(isset($fields['last_name']) ? $fields['last_name'] : '')?>" autocomplete="off" required>
                                    <hr>
                                    <label for="last_name">Nama Belakang</label>
                                </div>
                            </div>
                        </div>

                        <div class="material fields has-2">
                            <div class="field">
                                <div class="control">
                                    <input type="password" name="password" id="password" class="input"
                                        placeholder="Password" value="<?=(isset($fields['password']) ? $fields['password'] : '')?>" autocomplete="off" required>
                                    <hr>
                                    <label for="password">Password</label>
                                </div>
                            </div>
                        
                            <div class="field">
                                <div class="control">
                                    <input type="password" name="repassword" id="repassword" class="input"
                                        placeholder="Ulangi Password" value="<?=(isset($fields['repassword']) ? $fields['repassword'] : '')?>" autocomplete="off" required>
                                    <hr>
                                    <label for="repassword">Ulangi Password</label>
                                </div>
                            </div>
                        </div>

                        <div class="material field w25">
                            <div class="control">
                                <select name="role_id" id="role_id" class="input select is-filled" required="">
                                    <?php foreach ($roles as $role) : ?>
                                        <option value="<?=escHtmlAttr($role->role_id)?>" <?=(isset($fields['role_id']) && $fields['role_id'] === $role->role_id ? 'selected' : '')?>>
                                            <?=escHtml($role->name)?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <hr>
                                <label for="role_id">Role</label>
                            </div>
                        </div>

                        <div class="material fields has-2">
                            <div class="field">
                                <div class="control">
                                    <input type="email" name="email" id="email"
                                        class="input" placeholder="Alamat Email" value="<?=(isset($fields['email']) ? $fields['email'] : '')?>"
                                        autocomplete="off" required>
                                    <hr>
                                    <label for="email">Alamat Email</label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="text" name="phone" id="phone"
                                        class="input" placeholder="Nomor Telepon" value="<?=(isset($fields['phone']) ? $fields['phone'] : '')?>"
                                        autocomplete="off" required>
                                    <hr>
                                    <label for="phone">Nomor Telepon</label>
                                </div>
                            </div>
                        </div>

                        <div class="material field">
                            <div class="control">
                                <textarea name="address" id="address" class="input textarea" placeholder="Alamat Lengkap" autocomplete="off" required><?=(isset($fields['address']) ? $fields['address'] : '')?></textarea>
                                <hr>
                                <label for="address">Alamat Lengkap</label>
                            </div>
                        </div>

                    </div><!-- .panel-content -->

                </div><!-- .panel -->
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>