<?php
/**
 * Created by PhpStorm.
 * User: LCH
 * Date: 2015/7/11
 * Time: 14:46
 */
namespace p2p\withdraw\controllers\backend;

use kiwi\helpers\ArrayHelper;
use kiwi\Kiwi;
use Yii;

class WithdrawFailController extends WithdrawRecordController
{

    /**
     * Lists all WithdrawRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $withdrawClass = Kiwi::getWithdrawRecordClass();
        $searchModel = Kiwi::getWithdrawRecordSearch();
        $dataProvider = $searchModel->search(ArrayHelper::merge(Yii::$app->request->queryParams, [
            'WithdrawRecordSearch' => [
                'deposit_type' => $withdrawClass::TYPE_MANUAL,
                'status' => $withdrawClass::STATUS_FAIL
            ]]));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $withdrawClass::STATUS_FAIL
        ]);
    }
}