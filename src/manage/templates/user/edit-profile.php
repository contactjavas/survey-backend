<?php require __DIR__ . '/../header.php'; ?>

<div class="left-layout">
    <?php require __DIR__ . '/../sidebar.php'; ?>
</div><!-- .left-layout -->
<div class="right-layout">
    <div class="topbar">
        <div class="action-bar">
            <div class="left-action-bar">
                <h3>Edit Profil</h3>
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="<?=escHtmlAttr($activeMenu)?>">Profil</a>
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
            <form name="add_survey" action="/manage/users/edit/<?=escHtmlAttr($user['id'])?>/" method="POST">
                <input type="hidden" name="id" value="<?=escHtmlAttr($user['id'])?>">
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
                                    Profil Pengguna
                                </h3>
                            </header>

                            <div class="panel-content">

                                <div class="material fields has-2">
                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="first_name" id="first_name" class="input"
                                                placeholder="Nama Depan" value="<?=escHtmlAttr($user->first_name)?>" autocomplete="off" required>
                                            <hr>
                                            <label for="first_name">Nama Depan</label>
                                        </div>
                                    </div>
                                
                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="last_name" id="last_name" class="input"
                                                placeholder="Nama Belakang" value="<?=escHtmlAttr($user->last_name)?>" autocomplete="off" required>
                                            <hr>
                                            <label for="last_name">Nama Belakang</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material field w25">
                                    <div class="control">
                                        <select name="role_id" id="role_id" class="input select is-filled" required="">
                                            <?php foreach ($roles as $role) : ?>
                                                <option value="<?=escHtmlAttr($role->role_id)?>" <?=($role->role_id === $user->roles[0]->role_id ? 'selected' : '')?>>
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
                                                class="input" placeholder="Alamat Email" value="<?=escHtmlAttr($user->email)?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="email">Alamat Email</label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type="text" name="phone" id="phone"
                                                class="input" placeholder="Nomor Telepon" value="<?=escHtmlAttr($user->phone)?>"
                                                autocomplete="off" required>
                                            <hr>
                                            <label for="phone">Nomor Telepon</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="material field">
                                    <div class="control">
                                        <textarea name="address" id="address" class="input textarea" placeholder="Alamat Lengkap" autocomplete="off" required><?=$user->address?></textarea>
                                        <hr>
                                        <label for="address">Alamat Lengkap</label>
                                    </div>
                                </div>

                            </div><!-- .panel-content -->

                        </div><!-- .panel -->
                    </div>
                    <div class="side-edit-area">
                        <div class="panel">
                            <header class="panel-header">
                                <h3 class="panel-title">
                                    Ubah Password
                                </h3>
                            </header>

                            <div class="panel-content">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div>

<?php require __DIR__ . '/../footer.php'; ?>