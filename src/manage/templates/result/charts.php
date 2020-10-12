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
                <h2 class="page-title">Hasil Survey</h2>
            </div>
            <div class="column w50 right-titlebar">
                <nav class="breadcrumb">
                    <ul>
                        <li>
                            <a href="<?=escHtmlAttr($activeMenu)?>">Survey</a>
                        </li>
                        <li class="is-active">
                            <span>Hasil Survey</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div><!-- .titlebar -->
        
        <div class="main-content">
            <form name="survey_result_filter" action="" method="POST">

                <div class="panel filter-panel">
                    <header class="panel-header">
                        <h3 class="panel-title">
                            Penyaringan berdasarkan data responden:
                        </h3>
                    </header>
                    <div class="panel-content">

                        <div class="material fields has-4">

                            <div class="field">
                                <div class="control">
                                    <select name="gender_id" id="gender_id" class="input select is-filled filter-param">
                                        <option value="">Semua</option>
                                        <?php foreach ($genders as $gender) : ?>
                                            <option value="<?=escHtmlAttr($gender->id)?>">
                                                <?=escHtml($gender->name)?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="gender_id">Jenis Kelamin</label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <select name="age_range" id="age_range" class="input select is-filled filter-param">
                                        <option value="">Semua</option>
                                        <option value="17 - 22">( 17 - 22 )</option>
                                        <option value="23 - 35">( 23 - 35 )</option>
                                        <option value="36 - 50">( 36 - 50 )</option>
                                        <option value=">50">( >50 )</option>
                                    </select>
                                    <label for="age_range">Usia</label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <select name="religion_id" id="religion_id" class="input select is-filled filter-param">
                                        <option value="">Semua</option>
                                        <?php foreach ($religions as $religion) : ?>
                                            <option value="<?=escHtmlAttr($religion->id)?>">
                                                <?=escHtml($religion->name)?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="religion_id">Agama</label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <select name="education_id" id="education_id" class="input select is-filled filter-param">
                                        <option value="">Semua</option>
                                        <?php foreach ($educations as $education) : ?>
                                            <option value="<?=escHtmlAttr($education->id)?>">
                                                <?=escHtml($education->name)?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="education_id">Pendidikan Terakhir</label>
                                </div>
                            </div>

                        </div><!-- .fields -->

                        <div class="material fields has-4">

                            <div class="field">
                                <div class="control">
                                    <select name="job" id="job" class="input select is-filled filter-param">
                                        <option value="">Semua</option>
                                        <option value="ASN">ASN</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Pegawai">Pegawai</option>
                                        <option value="Pelajar/ Mahasiswa">Pelajar/ Mahasiswa</option>
                                        <option value="Lain- lain">Lain- lain</option>
                                    </select>
                                    <label for="job">Pekerjaan</label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <select name="income_range" id="income_range" class="input select is-filled filter-param">
                                        <option value="">Semua</option>
                                        <option value="Rp. 0 - Rp. 1.500.000 ,00">Rp. 0 - Rp. 1.500.000 ,00</option>
                                        <option value="Rp. 1.500.000 ,00 - Rp. 3.000.000 ,00">Rp. 1.500.000 ,00 - Rp. 3.000.000 ,00</option>
                                        <option value="Rp. 3.000.000 ,00 - Rp. 6.000.000 ,00">Rp. 3.000.000 ,00 - Rp. 6.000.000 ,00</option>
                                        <option value="> Rp. 6.000.000 ,00"> > Rp. 6.000.000 ,00</option>
                                    </select>
                                    <label for="income_range">Penghasilan</label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <select name="active_on_social_media" id="active_on_social_media" class="input select is-filled filter-param">
                                        <option value="">Semua</option>
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                    <label for="active_on_social_media">Aktif di media sosial</label>
                                </div>
                            </div>
                                            
                        </div>
        
                    </div><!-- .panel-content -->

                    <footer class="panel-footer">
                        <button class="button is-secondary submit-button">Filter Hasil Survey</button>
                    </footer><!-- .panel-footer -->
                </div><!-- .panel -->

                <script>
                var surveyOpts = {
                    id: <?=$surveyId?>
                };
                var surveyCharts = [];
                </script>

                <?php foreach ($questions as $question_number => $question) : ?>
                    <?php
                    $labels = '';
                    $data   = '';

                    $bgColors     = '';
                    $borderColors = '';
                    
                    foreach ($question->choices as $choice) :
                        $labels .= '"' . $choice->title . '", ';
                        $data   .= $choice->totalVotes . ', ';

                        $color         = 'rgba(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', opacity)';
                        $bgColors     .= '"' . str_ireplace('opacity', '0.7', $color) . '", ';
                        $borderColors .= '"' . str_ireplace('opacity', '1', $color) . '", ';
                    endforeach;
                    
                    $labels = rtrim($labels, ' ,');
                    $labels = '[' . $labels . ']';
                    
                    $data = rtrim($data, ' ,');
                    $data = '[' . $data . ']';
                    
                    $bgColors = rtrim($bgColors, ' ,');
                    $bgColors = '[' . $bgColors . ']';
                    
                    $borderColors = rtrim($borderColors, ' ,');
                    $borderColors = '[' . $borderColors . ']';
                    ?>

                    <script>
                        surveyCharts.push({
                            id: <?=$question->id?>,
                            selector: '#question-chart-<?=$question->id?>',
                            labels: <?=$labels?>,
                            data: <?=$data?>,
                            bgColors: <?=$bgColors?>,
                            borderColors: <?=$borderColors?>,
                        });
                    </script>

                    <div class="panel chart-panel">
                        <header class="panel-header">
                            <h3 class="panel-title">
                                Pertanyaan #<?=escHtml($question_number + 1)?>
                            </h3>
                        </header>
                        <div class="panel-content">

                            <div class="panel-info">
                                <h4 class="info-title">
                                    <?=escHtml($question->type)?> 
                                </h4>
                                <p>
                                    <?=escHtml($question->title)?> 
                                </p>
                            </div>

                            <div class="question-charts">
                                <div class="question-chart">
                                    <canvas id="question-chart-<?=escHtmlAttr($question->id)?>" class="question-chart-canvas"></canvas>
                                </div>
                            </div>
            
                        </div>
                    </div><!-- .panel -->
                <?php endforeach; ?>
            </form>
        </div><!-- .main-content -->
    </div><!-- .main-screen -->
</div><!-- .right-layout -->

<?php require __DIR__ . '/../footer.php'; ?>