<?php

use kiwi\Kiwi;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model p2p\activity\models\Activity */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 2],
        'fullSpan' => 11
    ]); ?>

    <?= $form->field($model, 'activity_type')->dropDownList(Yii::$app->dataList->activityTypes) ?>

    <?= $form->field($model, 'activity_send_type')->dropDownList(Yii::$app->dataList->activitySendTypes) ?>

    <?= $form->field($model, 'activity_send_value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valid_date')->textInput() ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-9">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('p2p_activity', 'Create') : Yii::t('p2p_activity', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
