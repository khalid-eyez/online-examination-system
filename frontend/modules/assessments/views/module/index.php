<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\assessments\models\ModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">


    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Create Module', ['create'], ['class' => 'btn btn-success float-right mb-2']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'moduleName',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
