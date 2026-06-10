<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\panels\RendersSummaryChartTrait;
use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditMail;
use Yii;

/**
 * DefaultController
 * @package bedezign\yii2\audit\controllers
 */
class DefaultController extends Controller
{
    use RendersSummaryChartTrait;

    /**
     * Module Default Action.
     * @return mixed
     */
    public function actionIndex()
    {
        $chartData = $this->getChartData();
        $startDate = date('Y-m-d 00:00:00', strtotime('-6 days'));
        $endDate   = date('Y-m-d 23:59:59');

        $stats = [
            'entries_total'   => AuditEntry::find()->count(),
            'entries_week'    => AuditEntry::find()->where(['between', 'created', $startDate, $endDate])->count(),
            'trails_total'    => AuditTrail::find()->count(),
            'trails_week'     => AuditTrail::find()->where(['between', 'created', $startDate, $endDate])->count(),
            'errors_total'    => AuditError::find()->count(),
            'errors_week'     => AuditError::find()->where(['between', 'created', $startDate, $endDate])->count(),
            'mails_total'     => AuditMail::find()->count(),
            'mails_week'      => AuditMail::find()->where(['between', 'created', $startDate, $endDate])->count(),
            'avg_duration'    => round((float) AuditEntry::find()
                ->where(['between', 'created', $startDate, $endDate])
                ->average('duration'), 3),
        ];

        return $this->render('index', ['chartData' => $chartData, 'stats' => $stats]);
    }

    protected function getChartModel()
    {
        return AuditEntry::className();
    }
}
