<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\web\AuditChartAsset;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $chartData array */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['breadcrumbs'][] = $this->title;

AuditChartAsset::register($this);
$this->registerCss('canvas {width: 100% !important; height: 400px;}');

$mainId = 'audit-chart-main';
$mainJs = 'new Chart(document.getElementById(' . Json::encode($mainId) . '), {'
    . 'type:"bar",'
    . 'data:{labels:' . Json::encode(array_keys($chartData)) . ',datasets:[{'
    . 'backgroundColor:"rgba(151,187,205,0.5)",'
    . 'borderColor:"rgba(151,187,205,1)",'
    . 'data:' . Json::encode(array_values($chartData))
    . '}]},'
    . 'options:{plugins:{legend:{display:false},tooltip:{enabled:false}}}'
    . '});';
$this->registerJs($mainJs);
?>
<div class="audit-index">

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <h2><?php echo Html::a(Yii::t('audit', 'Entries'), ['entry/index']); ?></h2>

            <div class="well">
                <canvas id="<?= $mainId ?>"></canvas>
            </div>
        </div>

        <?php foreach (Audit::getInstance()->panels as $panel): ?>
            <?php
            /** @var Panel $panel */
            $chart = $panel->getChart();
            if (!$chart) continue;
            $indexUrl = $panel->getIndexUrl();
            ?>
            <div class="col-md-3 col-lg-3">
                <h2><?php echo $indexUrl ? Html::a($panel->getName(), $indexUrl) : $panel->getName(); ?></h2>
                <div class="well">
                    <?php echo $chart; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>
