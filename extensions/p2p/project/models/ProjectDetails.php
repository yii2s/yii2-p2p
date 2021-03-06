<?php

namespace p2p\project\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project_details".
 *
 * @property integer $project_details_id
 * @property integer $project_id
 * @property string $project_introduce
 * @property string $loan_person_info
 * @property string $repayment_source
 * @property string $collateral_info
 * @property string $risk_control_info
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class ProjectDetails extends \kiwi\db\ActiveRecord
{
    public static $enableLogicDelete = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_introduce', 'loan_person_info', 'repayment_source', 'collateral_info', 'risk_control_info'], 'required'],
            [['repayment_source', 'collateral_info', 'risk_control_info'], 'string'],
            [['project_introduce'], 'string', 'max' => 256],
            [['loan_person_info'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_details_id' => Yii::t('p2p_project', 'Project Details ID'),
            'project_id' => Yii::t('p2p_project', 'Project ID'),
            'project_introduce' => Yii::t('p2p_project', 'Project Introduce'),
            'loan_person_info' => Yii::t('p2p_project', 'Loan Person Info'),
            'repayment_source' => Yii::t('p2p_project', 'Repayment Source'),
            'collateral_info' => Yii::t('p2p_project', 'Collateral Info'),
            'risk_control_info' => Yii::t('p2p_project', 'Risk Control Info'),
            'create_time' => Yii::t('p2p_project', 'Create Time'),
            'update_time' => Yii::t('p2p_project', 'Update Time'),
            'is_delete' => Yii::t('p2p_project', 'Is Delete'),
        ];
    }

    public function behaviors()
    {
        return [
            'time' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
            ]
        ];
    }
}
