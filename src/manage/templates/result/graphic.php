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
            <form name="survey_list" action="" method="POST">
                <script>
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

                    <div class="panel">
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