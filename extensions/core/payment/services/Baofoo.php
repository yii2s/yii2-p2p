<?php
/**
 * @author Lujie.Zhou(lujie.zhou@jago-ag.cn)
 * @Date 12/17/2014
 * @Time 11:39 AM
 */

namespace core\payment\services;


use kiwi\payment\BasePaymentMethod;

class Baofoo extends BasePaymentMethod
{
    public $requestUrl = 'http://gw.baofoo.com/payindex';

    public $memberID;

    public $terminalID;

    public $pKey;

    public $productName = '';

    public function generateId()
    {
        return date("YmdHis") . mt_rand(1000, 9999);
    }

    public function prepareRequestData($money)
    {
        $requestData = [
            'MemberID' => $this->memberID,
            'TerminalID' => $this->terminalID,
            'InterfaceVersion' => '4.0',    //接口版本号
            'KeyType' => '1',   //接口版本号
            'PayID' => '',
            'TradeDate' => date("YmdHis"),  //交易时间
            'TransID' => $this->getId(),    //流水号
            'OrderMoney' => $money * 100,
            'ProductName' => $this->productName,
            'Amount' => "1",
            'Username' => "",
            'AdditionalInfo' => "",
            'PageUrl' => $this->returnUrl,
            'ReturnUrl' => $this->callbackUrl,
            'NoticeType' => '1',
        ];
        $requestData['Signature'] = $this->getRequestSignature($requestData);
        return $requestData;
    }

    public function getRequestSignature($data)
    {
        $md5 = '';
        $signKeys = ["MemberID", "PayID", "TradeDate", "TransID", "OrderMoney", "PageUrl", "ReturnUrl", "NoticeType"];
        foreach ($signKeys as $key) {
            if (isset($data[$key])) {
                $md5 .= $data[$key] . '|';
            }
        }
        $md5 .= $this->pKey;
        $md5 = md5($md5);
        return $md5;
    }

    public function getCallbackSignature($data)
    {
        $md5 = '';
        $signKeys = ["MemberID", "TerminalID", "TransID", "Result", "ResultDesc", "FactMoney", "AdditionalInfo", 'SuccTime'];
        foreach ($signKeys as $key) {
            if (isset($data[$key])) {
                $md5 .= $key . '=' . $data[$key] . '~|~';
            }
        }
        $md5 .= 'Md5Sign=' . $this->pKey;
        $md5 = md5($md5);
        return $md5;
    }

    /**
     * @inheritdoc
     */
    public function validateCallbackData($data)
    {
        $signStr = $this->getCallbackSignature($data);
        return isset($data['Md5Sign']) && strtoupper($data['Md5Sign']) == strtoupper($signStr);
    }

    /**
     * @inheritdoc
     */
    public function validatePaymentStatus($data)
    {
        return isset($data['Result']) && $data['status'] == 1;
    }

    /**
     * @inheritdoc
     */
    public function getCallbackId($data)
    {
        return isset($data['TransID']) ? $data['TransID'] : false;
    }
} 