<?php

namespace bedezign\yii2\audit\web;

use yii\web\AssetBundle;
use yii\web\View;

class AuditChartAsset extends AssetBundle
{
    public $js = [
        'https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js',
    ];

    public $jsOptions = ['position' => View::POS_HEAD];
}
