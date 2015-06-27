<?php
/**
 * @author Cangzhou.Wu(wucangzhou@gmail.com)
 * @Date 2015/6/27
 * @Time 14:25
 */

namespace core\member\models;


use kiwi\base\Model;
use kiwi\Kiwi;
use Yii;

class BindEmailForm extends Model
{
    public $email;


    public function rules()
    {
        return [
            ['email', 'email'],
        ];
    }

    /**
     * Sends an email with a link, for binding the email.
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        $memberModel = Kiwi::getMember()->find()->where(['member_id' => Yii::$app->user->id])->one();
        if ($memberModel) {
            $memberModel->email_verify_token = Yii::$app->security->generateRandomString() . '_' . time();
            $memberStatusModel = $memberModel->memberStatus;
            if ($memberStatusModel->email_status) {
                $email = $memberModel->email;
            } else {
                $email = $this->email;
            }
            if ($memberModel->save()) {
                return Yii::$app->mailer->compose('emailResetToken', ['email' => $memberModel, 'user' => Yii::$app->user->identity])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($email)
                    ->setSubject('email reset for ' . Yii::$app->name)
                    ->send();
            }
        }
        return false;
    }

    public function setEmailStatus($token)
    {
        $memberModel = Kiwi::getMember()->find()->where(array('email_verify_token' => $token))->one();
        if ($memberModel) {
            $memberStatusModel = $memberModel->memberStatus;
            if ($memberStatusModel->email_status == 0) {
                $memberStatusModel->email_status = 1;
                if ($memberStatusModel->save()) {
                    return true;
                }
            } else {
                $memberStatusModel->email_status = 0;
                if ($memberStatusModel->save()) {
                    return true;
                }
            }
        }
        return false;
    }

} 