<?php
/* @var $this yii\web\View */
/* @var $chartData array */

use bedezign\yii2\audit\web\AuditChartAsset;
use yii\helpers\Json;

AuditChartAsset::register($this);

$id = 'audit-chart-mail-' . uniqid();
$js = 'new Chart(document.getElementById(' . Json::encode($id) . '), {'
    . 'type:"bar",'
    . 'data:{labels:' . Json::encode(array_keys($chartData)) . ',datasets:[{'
    . 'backgroundColor:"rgba(151,187,205,0.5)",'
    . 'borderColor:"rgba(151,187,205,1)",'
    . 'data:' . Json::encode(array_values($chartData))
    . '}]},'
    . 'options:{plugins:{legend:{display:false},tooltip:{enabled:false}}}'
    . '});';
$this->registerJs($js);
?>
<canvas id="<?= $id ?>"></canvas>
