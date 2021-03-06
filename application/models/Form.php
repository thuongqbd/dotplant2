<?php

namespace app\models;

use app\properties\AbstractModel;
use app\properties\HasProperties;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "form".
 *
 * @property integer $id
 * @property string $name
 * @property string $form_view
 * @property string $form_success_view
 * @property string $email_notification_addresses
 * @property string $email_notification_view
 * @property string $form_open_analytics_action_id
 * @property string $form_submit_analytics_action_id
 * @property AbstractModel $abstractModel
 */
class Form extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => HasProperties::className(),
            ],
            [
                'class' => \devgroup\TagDependencyHelper\ActiveRecordHelper::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email_notification_addresses'], 'required'],
            [
                ['name', 'form_view', 'form_success_view', 'email_notification_addresses', 'email_notification_view'],
                'string'
            ],
            [['form_open_analytics_action_id', 'form_submit_analytics_action_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'form_view' => Yii::t('app', 'Form View'),
            'form_success_view' => Yii::t('app', 'Form Success View'),
            'email_notification_addresses' => Yii::t('app', 'Email Notification Addresses'),
            'email_notification_view' => Yii::t('app', 'Email Notification View'),
            'form_open_analytics_action_id' => Yii::t('app', 'Form Open Analytics Action ID'),
            'form_submit_analytics_action_id' => Yii::t('app', 'Form Submit Analytics Action ID'),
            'properties' => Yii::t('app', 'Properties'),
        ];
    }

    public function search($params)
    {
        /* @var $query \yii\db\ActiveQuery */
        $query = self::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        if (!($this->load($params))) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'form_view', $this->form_view]);
        $query->andFilterWhere(['like', 'form_success_view', $this->form_success_view]);
        $query->andFilterWhere(['like', 'email_notification_addresses', $this->email_notification_addresses]);
        $query->andFilterWhere(['like', 'email_notification_view', $this->email_notification_view]);
        $query->andFilterWhere(['form_open_analytics_action_id', $this->form_open_analytics_action_id]);
        $query->andFilterWhere(['form_submit_analytics_action_id', $this->form_submit_analytics_action_id]);

        return $dataProvider;
    }

    public function getSubmissions()
    {
        return $this->hasMany(Submission::className(), ['form_id' => 'id'])->inverseOf('form');
    }
}
