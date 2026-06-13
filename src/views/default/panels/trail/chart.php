<?php
/* @var $this yii\web\View */
/* @var $panel bedezign\yii2\audit\panels\TrailPanel */
/* @var $chartData array */

use bedezign\yii2\audit\web\AuditChartAsset;
use yii\helpers\Json;

AuditChartAsset::register($this);

$id     = 'audit-chart-trail-' . uniqid();
$idJson = Json::encode($id);
$labels = Json::encode(array_keys($chartData));
$values = Json::encode(array_values($chartData));

$this->registerJs(<<<JS
(function() {
    var ctx = document.getElementById($idJson).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: $labels,
            datasets: [{
                backgroundColor: 'rgba(16,185,129,0.5)',
                borderColor: 'rgba(16,185,129,1)',
                borderWidth: 1.5,
                data: $values
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true, callbacks: { label: function(c) { return ' ' + c.parsed.y + ' trail(s)'; } } }
            },
            scales: {
                x: { ticks: { font: { size: 10 }, color: '#6b7280', autoSkip: false, maxRotation: 30 }, grid: { display: false } },
                y: { min: 0, suggestedMax: 1, ticks: { precision: 0, font: { size: 10 }, color: '#6b7280' } }
            }
        }
    });
})();
JS);
?>
<div style="position:relative; height:260px;">
    <canvas id="<?= $id ?>"></canvas>
</div>
