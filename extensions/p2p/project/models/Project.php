<?php

namespace p2p\project\models;

use kiwi\Kiwi;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project".
 *
 * @property integer $project_id
 * @property string $project_name
 * @property string $project_no
 * @property integer $invest_total_money
 * @property string $interest_rate
 * @property integer $repayment_date
 * @property integer $repayment_type
 * @property integer $release_date
 * @property string $project_type
 * @property string $create_user
 * @property integer $invested_money
 * @property string $verify_user
 * @property integer $verify_date
 * @property integer $min_money
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 *
 */
class Project extends \kiwi\db\ActiveRecord
{
    use ProjectTrait;

    public static $enableLogicDelete = true;

    public static $enableCascadeDelete = true;

    public static $cascadeDeleteRelations = ['ProjectDetails', 'ProjectLegalOpinion', 'ProjectMaterial'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name', 'project_no', 'invest_total_money', 'interest_rate', 'repayment_date', 'repayment_type', 'release_date', 'project_type', 'verify_date', 'min_money'], 'required'],
            [['invest_total_money', 'repayment_type', 'invested_money', 'min_money', 'status'], 'integer'],
            [['interest_rate'], 'number'],
            [['verify_user'], 'string'],
            [['project_name'], 'string', 'max' => 100],
            [['project_no'], 'string', 'max' => 30],
            [['project_type'], 'string', 'max' => 20],
            ['repayment_date', 'date', 'format' => 'yyyy-MM-dd HH:mm', 'timestampAttribute' => 'repayment_date', 'on' => ['insert']],
            ['release_date', 'date', 'format' => 'yyyy-MM-dd HH:mm', 'timestampAttribute' => 'release_date', 'on' => ['insert']],
            ['verify_date', 'date', 'format' => 'yyyy-MM-dd HH:mm', 'timestampAttribute' => 'verify_date', 'on' => ['insert']],
            [['repayment_date', 'release_date', 'verify_date'], 'validateDate', 'on' => ['insert']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => Yii::t('p2p_project', 'Project ID'),
            'project_name' => Yii::t('p2p_project', 'Project Name'),
            'project_no' => Yii::t('p2p_project', 'Project No'),
            'invest_total_money' => Yii::t('p2p_project', 'Invest Total Money'),
            'interest_rate' => Yii::t('p2p_project', 'Interest Rate'),
            'repayment_date' => Yii::t('p2p_project', 'Repayment Date'),
            'repayment_type' => Yii::t('p2p_project', 'Repayment Type'),
            'release_date' => Yii::t('p2p_project', 'Release Date'),
            'project_type' => Yii::t('p2p_project', 'Project Type'),
            'create_user' => Yii::t('p2p_project', 'Create User'),
            'invested_money' => Yii::t('p2p_project', 'Invested Money'),
            'verify_user' => Yii::t('p2p_project', 'Verify User'),
            'verify_date' => Yii::t('p2p_project', 'Verify Date'),
            'min_money' => Yii::t('p2p_project', 'Min Money'),
            'status' => Yii::t('p2p_project', 'Status'),
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
            ],
            'user' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user',
                'updatedByAttribute' => false,
            ]
        ];
    }

    public function validateDate()
    {
        if($this->repayment_date < time()) {
            $this->addError('repayment_date', 'repayment_date不能早于当前时间！');
        }
        if($this->release_date < time()) {
            $this->addError('release_date', 'release_date不能早于当前时间！');
        }
        if($this->verify_date < time()) {
            $this->addError('verify_date', 'verify_date不能早于当前时间！');
        }
    }
}