<?php
/**
 * Created by shalvah
 * Date: 9/1/17
 * Time: 12:10 PM
 */

namespace bedezign\yii2\audit\components\panels;

/**
 * Trait RendersSummaryChartTrait
 * @package bedezign\yii2\audit\components\panels
 *
 * Used by audit panels or controllers which want to render a summary chart in their view
 */
trait RendersSummaryChartTrait
{
    /**
     * The name of the model for which the chart should be rendered
     *
     * @return string Fully namespaced class name
     */
    protected function getChartModel()
    {
        return static::className();
    }

    /**
     * Return audit data for the last seven days
     * to be rendered on the chart
     *
     * @return array
     */
    protected function getChartData()
    {
        static $frDays = ['Mon' => 'Lun', 'Tue' => 'Mar', 'Wed' => 'Mer', 'Thu' => 'Jeu', 'Fri' => 'Ven', 'Sat' => 'Sam', 'Sun' => 'Dim'];

        $defaults  = [];
        $startDate = strtotime('-6 days');
        foreach (range(-6, 0) as $day) {
            $ts    = strtotime($day . ' days');
            $label = $frDays[date('D', $ts)] . '. ' . date('d/m', $ts);
            $defaults[$label] = 0;
        }

        $panelModel = $this->getChartModel();
        $results = $panelModel::find()
            ->select(["COUNT(DISTINCT id) as count", "created AS day"])
            ->where(['between', 'created',
                date('Y-m-d 00:00:00', $startDate),
                date('Y-m-d 23:59:59')])
            ->groupBy("created")->indexBy('day')->column();

        foreach ($results as $date => $count) {
            $ts    = strtotime($date);
            $label = $frDays[date('D', $ts)] . '. ' . date('d/m', $ts);
            if (isset($defaults[$label])) {
                $defaults[$label] += $count;
            }
        }
        return $defaults;
    }
}
