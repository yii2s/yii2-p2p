<?php
/**
 * @link http://www.yincart.com/
 * @copyright Copyright (c) 2014 Yincart
 * @license http://www.yincart.com/license/
 */

namespace p2p\recharge;


use kiwi\Kiwi;
use kiwi\payment\BasePayment;
use kiwi\payment\PaymentEvent;
use yii\base\BootstrapInterface;
use yii\base\Exception;
use yii\helpers\Json;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {

    }

    public function attachEvents($app)
    {
        PaymentEvent::on(BasePayment::className(), BasePayment::EVENT_FINISH_PAY, [$this, 'finishPay']);
    }

    /**
     * @param \kiwi\payment\PaymentEvent $event
     * @throws Exception
     */
    public function finishPay($event)
    {
        $rechargeRecordClass = Kiwi::getRechargeRecordClass();
        $rechargeRecord = $rechargeRecordClass::findByTransactionId($event->transactionId);
        if ($event->isError) {
            $rechargeRecord->status = $rechargeRecord::STATUS_ERROR;
        } else {
            if ($event->isSuccessful) {
                $rechargeRecord->status = $rechargeRecord::STATUS_SUCCESS;
            } else {
                $rechargeRecord->status = $rechargeRecord::STATUS_FAIL;
            }
        }
        if (!$rechargeRecord->save()) {
            throw new Exception('Update recharge record error: ' . Json::encode($rechargeRecord->getErrors()));
        }
    }
} 