<?php

use kiwi\Kiwi;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model p2p\project\models\Project */
/* @var $form kartik\widgets\ActiveForm */

$js = <<<EOF
$(".btn-page").click(function() {
$("#w0 li:eq(" + $(this).data("tab") + ")  a").tab("show");
});
EOF;
$this->registerJs($js, $this::POS_END);

if (isset($model->repayment_date) && isset($model->release_date)) {
    $model->repayment_date = date('Y-m-d', $model->repayment_date);
    $model->release_date = date('Y-m-d', $model->release_date);
    $repayment_date = $model->repayment_date;
    $release_date = $model->release_date;
} else {
    $repayment_date = date('Y-m-d', time());
    $release_date = date('Y-m-d', time());
}
$projectClass = Kiwi::getProjectClass();
if (!isset($model->status)) {
    $model->status = $projectClass::STATUS_PENDING;
}

$disabled = $model->status == $projectClass::STATUS_PENDING ? false : 'disabled';

?>
<div class="project-form">

    <?php $form = ActiveForm::begin([
        'id' => 'project-form-horizontal',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 2],
        'fullSpan' => 11,
        'disabled' => $disabled
    ]);
    $fieldGroups = [];
    $fields = ['<br />'];
    $fields[] = $form->field($model, 'project_name')->textInput(['maxlength' => 255]);
    $fields[] = $form->field($model, 'project_no')->textInput(['maxlength' => 255]);
    $fields[] = $form->field($model, 'invest_total_money')->textInput(['maxlength' => 255]);
    $fields[] = $form->field($model, 'interest_rate')->textInput(['maxlength' => 255]);
    $fields[] = $form->field($model, 'repayment_date')->widget(DatePicker::className(), [
        'options' => [
            'value' => $repayment_date
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'language' => Yii::$app->language,
            'autoclose' => true,
        ]
    ]);

    $fields[] = $form->field($model, 'repayment_type')->dropDownList(Yii::$app->dataList->projectRepaymentType);
    $fields[] = $form->field($model, 'release_date')->widget(DatePicker::className(), [
        'options' => [
            'value' => $release_date,
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'language' => Yii::$app->language,
            'autoclose' => true,
        ]
    ]);
    $fields[] = $form->field($model, 'project_type')->dropDownList(Yii::$app->dataList->projectType);
    $fields[] = $form->field($model, 'invested_money')->textInput([
        'value' => $model->invested_money ? $model->invested_money : 0,
        'disabled' => 'disabled'
    ]);
    //    $fields[] = $form->field($model, 'verify_user')->textInput(['maxlength' => 255]);
    //    $fields[] = $form->field($model, 'verify_date')->widget(DateTimePicker::className(),[
    //        'options' => [
    //            'value' => $verify_date
    //        ],
    //        'pluginOptions' => [
    //            'language' => Yii::$app->language,
    //            'autoclose'=>true,
    //        ]
    //    ]);
    $fields[] = $form->field($model, 'min_money')->textInput(['maxlength' => 255]);
    $fields[] = $form->field($model, 'status')->dropDownList([
        $model->status => Yii::$app->dataList->projectStatus[$model->status]
    ], ['disabled' => 'disabled']);
    $fields[] = '<div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <a class="btn btn-info btn-page pull-right" data-toggle="tab" href="#w0-tab1" data-tab="1">下一页</a>
                    </div>
                </div>';
    $fieldGroups[] = ['label' => Yii::t('p2p_project', 'Project Base Info'), 'content' => implode('', $fields)];

    $fields = ['<br />'];
    $projectDetails = $model->projectDetails ?: Kiwi::getProjectDetails();
    $fields[] = $form->field($projectDetails, 'project_introduce')->textarea();
    $fields[] = $form->field($projectDetails, 'loan_person_info')->textarea();
    $fields[] = $form->field($projectDetails, 'repayment_source')->textarea();
    $fields[] = $form->field($projectDetails, 'collateral_info')->textarea();
    $fields[] = $form->field($projectDetails, 'risk_control_info')->textarea();
    $fields[] = '<div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <a class="btn btn-info btn-page" data-toggle="tab" href="#w0-tab0" data-tab="0">上一页</a>
                        <a class="btn btn-info btn-page pull-right" data-toggle="tab" href="#w0-tab2" data-tab="2">下一页</a>
                    </div>
                </div>';
    $fieldGroups[] = ['label' => Yii::t('p2p_project', 'Project Details'), 'content' => implode('', $fields)];

    $fields = ['<br />'];
    $projectLegalOpinion = $model->projectLegalOpinion ?: Kiwi::getProjectLegalOpinion();
    $fields[] = $form->field($projectLegalOpinion, 'legal_info')->widget(CKEditor::className(), [
        'name' => 'legal_info',
        'editorOptions' => [
            'filebrowserBrowseUrl' => Url::to(['/elfinder/manager']),
            'preset' => 'standard',
            'language' => Yii::$app->language,
        ],
        'options' => [
            'disabled' => $disabled
        ]
    ]);
    $fields[] = '<div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <a class="btn btn-info btn-page" data-toggle="tab" href="#w0-tab1" data-tab="1">上一页</a>
                        <a class="btn btn-info btn-page pull-right" data-toggle="tab" href="#w0-tab3" data-tab="3">下一页</a>
                    </div>
                </div>';
    $fieldGroups[] = ['label' => Yii::t('p2p_project', 'Project Legal Opinion'), 'content' => implode('', $fields)];

    $fields = ['<br />'];
    $projectMaterial = $model->projectMaterial ?: Kiwi::getProjectMaterial();
    $fields[] = $form->field($projectMaterial, 'material')->widget(CKEditor::className(), [
        'name' => 'material',
        'editorOptions' => [
            'filebrowserBrowseUrl' => Url::to(['/elfinder/manager']),
            'preset' => 'standard',
            'language' => Yii::$app->language,
        ],
        'options' => [
            'disabled' => $disabled
        ]
    ]);
    if ($disabled != 'disabled') {
        $submitButton = Html::submitButton(
            $model->isNewRecord ? Yii::t('p2p_project', 'Create') : Yii::t('p2p_project', 'Update'),
            [
                'class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right'
            ]);
    } else {
        $submitButton = '';
    }
    $fields[] = '<div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <a class="btn btn-info btn-page" data-toggle="tab" href="#w0-tab2" data-tab="2">上一页</a>' .
        $submitButton
        . '</div></div>';
    $fieldGroups[] = ['label' => Yii::t('p2p_project', 'Project Material'), 'content' => implode('', $fields)];
    echo Tabs::widget(['items' => $fieldGroups]);
    ?>

    <?php ActiveForm::end(); ?>

</div>