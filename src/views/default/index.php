<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\web\AuditChartAsset;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $chartData array */
/* @var $stats array */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['breadcrumbs'][] = $this->title;

AuditChartAsset::register($this);

$this->registerCss('
.audit-stat-card {
    border-radius: 6px;
    padding: 18px 20px;
    color: #fff;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.audit-stat-card .stat-icon { font-size: 2.4rem; opacity: 0.35; }
.audit-stat-card .stat-value { font-size: 2.2rem; font-weight: 700; line-height: 1; }
.audit-stat-card .stat-label { font-size: 0.85rem; opacity: 0.9; margin-top: 4px; }
.audit-stat-card .stat-week  { font-size: 0.8rem; opacity: 0.75; margin-top: 2px; }
.bg-entries  { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.bg-trails   { background: linear-gradient(135deg, #10b981, #047857); }
.bg-errors   { background: linear-gradient(135deg, #ef4444, #b91c1c); }
.bg-mails    { background: linear-gradient(135deg, #f59e0b, #b45309); }
.audit-chart-card {
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 20px;
    margin-bottom: 20px;
}
.audit-chart-card h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 16px;
}
.audit-chart-card h4 a { color: #374151; }
.audit-chart-card h4 a:hover { color: #3b82f6; }
.panel-mini-chart canvas { max-height: 160px; }
');

$labels     = Json::encode(array_keys($chartData));
$values     = Json::encode(array_values($chartData));
$mainId     = 'audit-chart-main-' . uniqid();
$mainIdJson = Json::encode($mainId);

$this->registerJs(<<<JS
(function() {
    var ctx = document.getElementById($mainIdJson).getContext('2d');
    var gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(59,130,246,0.4)');
    gradient.addColorStop(1, 'rgba(59,130,246,0)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: $labels,
            datasets: [{
                label: 'Entrées',
                data: $values,
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#1e293b',
                    padding: 10,
                    callbacks: {
                        label: function(c) { return ' ' + c.parsed.y + ' entrée(s)'; }
                    }
                }
            },
            scales: {
                x: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { color: '#6b7280', font: { size: 11 } } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { color: '#6b7280', font: { size: 11 }, precision: 0 } }
            }
        }
    });
})();
JS);
?>

<div class="audit-index" style="padding: 10px 0;">

    <!-- KPI cards -->
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="audit-stat-card bg-entries">
                <div>
                    <div class="stat-value"><?= $stats['entries_total'] ?></div>
                    <div class="stat-label"><?= Yii::t('audit', 'Entries') ?></div>
                    <div class="stat-week">+<?= $stats['entries_week'] ?> cette semaine</div>
                </div>
                <div class="stat-icon">&#9776;</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="audit-stat-card bg-trails">
                <div>
                    <div class="stat-value"><?= $stats['trails_total'] ?></div>
                    <div class="stat-label">Trails</div>
                    <div class="stat-week">+<?= $stats['trails_week'] ?> cette semaine</div>
                </div>
                <div class="stat-icon">&#9998;</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="audit-stat-card bg-errors">
                <div>
                    <div class="stat-value"><?= $stats['errors_total'] ?></div>
                    <div class="stat-label">Erreurs</div>
                    <div class="stat-week">+<?= $stats['errors_week'] ?> cette semaine</div>
                </div>
                <div class="stat-icon">&#9888;</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="audit-stat-card bg-mails">
                <div>
                    <div class="stat-value"><?= $stats['avg_duration'] > 0 ? $stats['avg_duration'] . 's' : '—' ?></div>
                    <div class="stat-label">Durée moy. (7j)</div>
                    <div class="stat-week"><?= $stats['mails_total'] ?> mails &bull; +<?= $stats['mails_week'] ?> cette semaine</div>
                </div>
                <div class="stat-icon">&#9201;</div>
            </div>
        </div>
    </div>

    <!-- Main entries chart -->
    <div class="row">
        <div class="col-md-12">
            <div class="audit-chart-card">
                <h4><?= Html::a(Yii::t('audit', 'Entries'), ['entry/index']) ?> — 7 derniers jours</h4>
                <div style="position:relative; height:260px;">
                    <canvas id="<?= $mainId ?>"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel mini-charts -->
    <?php
    $panels = array_filter(Audit::getInstance()->panels, function ($p) { return (bool)$p->getChart(); });
    $panels = array_values($panels);
    $count  = count($panels);
    if ($count > 0):
        $colClass = $count >= 4 ? 'col-sm-6 col-md-3' : ($count === 3 ? 'col-sm-6 col-md-4' : 'col-sm-6 col-md-6');
    ?>
    <div class="row">
        <?php foreach ($panels as $panel): ?>
        <?php /** @var Panel $panel */ ?>
        <div class="<?= $colClass ?>">
            <div class="audit-chart-card panel-mini-chart">
                <h4><?php
                    $url = $panel->getIndexUrl();
                    echo $url ? Html::a($panel->getName(), $url) : $panel->getName();
                ?></h4>
                <?= $panel->getChart() ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>
