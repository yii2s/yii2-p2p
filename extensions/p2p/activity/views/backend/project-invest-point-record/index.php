<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel p2p\activity\searches\ProjectInvestPointRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('p2p_activity', 'Project Invest Point Records');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-invest-point-record-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('p2p_activity', 'Create Project Invest Point Record'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'project_invest_point_record',
            'project_invest_record_id',
            'project_id',
            'member_id',
            'point',
            // 'project_type',
            // 'create_time:datetime',
            // 'is_delete',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>