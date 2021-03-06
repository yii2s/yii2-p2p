<?php

namespace p2p\project\models;

use kiwi\behaviors\ChangeBehavior;
use kiwi\behaviors\RecordBehavior;
use kiwi\Kiwi;
use p2p\project\services\RepaymentBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project_invest".
 *
 * @property integer $project_invest_id
 * @property integer $project_id
 * @property integer $member_id
 * @property string $rate
 * @property integer $invest_money
 * @property string $interest_money
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $status
 * @property integer $is_delete
 * @property integer $actual_invest_money
 *
 * @property integer $investingProjectCount
 *
 * @property ConponAnnualRecord[] $conponAnnualRecords
 * @property ConponBonusRecord[] $conponBonusRecords
 * @property ConponCashRecord[] $conponCashRecords
 * @property \core\member\models\Member $member
 * @property Project $project
 * @property ProjectInvestEmpiricRecord[] $projectInvestPointRecords
 * @property ProjectRepayment[] $projectRepayments
 */
class ProjectInvest extends \kiwi\db\ActiveRecord
{
    use ProjectTrait;

    const STATUS_PENDING = 0;
    const STATUS_REPAYMENT = 1;
    const STATUS_FINISHED = 2;
    const STATUS_CANCELED = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_invest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invest_money', 'interest_money'], 'required'],
            [['invest_money', 'create_time', 'status', 'actual_invest_money'], 'integer'],
            [['rate', 'interest_money'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_invest_id' => Yii::t('p2p_project', 'Project Invest ID'),
            'project_id' => Yii::t('p2p_project', 'Project ID'),
            'member_id' => Yii::t('p2p_project', 'Member ID'),
            'rate' => Yii::t('p2p_project', 'Rate'),
            'invest_money' => Yii::t('p2p_project', 'Invest Money'),
            'interest_money' => Yii::t('p2p_project', 'Interest Money'),
            'create_time' => Yii::t('p2p_project', 'Create Time'),
            'update_time' => Yii::t('p2p_project', 'Update Time'),
            'status' => Yii::t('p2p_project', 'Status'),
            'is_delete' => Yii::t('p2p_project', 'Is Delete'),
            'actual_invest_money' => Yii::t('p2p_project', 'Actual Invest Money'),
        ];
    }

    public function behaviors()
    {
        $changeRecordClass = Kiwi::getStatisticChangeRecordClass();
        return [
            'time' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
            ],
            'record' => [
                'class' => RecordBehavior::className(),
                'targetClass' => $changeRecordClass,
                'attributes' => [
                    'member_id'=> 'member_id',
                    'type' => $changeRecordClass::TYPE_INVEST,
                    'value' => function($invest) { return -$invest->actual_invest_money; },
                ],
            ],
            'change' => [
                'class' => ChangeBehavior::className(),
                'targetClass' => Kiwi::getProjectClass(),
                'attribute' => 'invested_money',
                'condition' => ['project_id' => $this->project_id],
                'valueAttribute' => 'invest_money',
                'resultAttribute' => false,
            ]
//            'repayment' => [
//                RepaymentBehavior::className(),
//            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConponAnnualRecords()
    {
        return $this->hasMany(ConponAnnualRecord::className(), ['project_invest_id' => 'project_invest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConponBonusRecords()
    {
        return $this->hasMany(ConponBonusRecord::className(), ['project_invest_id' => 'project_invest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConponCashRecords()
    {
        return $this->hasMany(ConponCashRecord::className(), ['project_invest_id' => 'project_invest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Kiwi::getMemberClass(), ['member_id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectInvestEmpiricRecords()
    {
        return $this->hasMany(Kiwi::getProjectInvestEmpiricRecordClass(), ['project_invest_id' => 'project_invest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRepayments()
    {
        return $this->hasMany(Kiwi::getProjectRepaymentClass(), [
            'project_invest_id' => 'project_invest_id',
            'project_id' => 'project_id',
            'member_id' => 'member_id',
        ]);
    }

    public function getInvestingProjectCount()
    {
        return static::find()
            ->andWhere(['member_id' => Yii::$app->user->id])
            ->andWhere(['status' => static::STATUS_REPAYMENT])
            ->select('project_id')
            ->distinct()
            ->count();
    }

    public function canTransfer()
    {
        if ($this->create_time <= strtotime('-3 month')) {
            return true;
        }
        return false;
    }
}
