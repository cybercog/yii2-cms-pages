<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model infoweb\partials\models\PagePartial */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('infoweb/pages', 'Page'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/pages', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'templates' => $templates
    ]) ?>

</div>