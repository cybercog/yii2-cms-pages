<?php

namespace infoweb\pages\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use dosamigos\translateable\TranslateableBehavior;
use infoweb\seo\models\Seo;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $template
 * @property integer $active
 * @property string $created_at
 * @property string $time_updated
 *
 * @property PagesLang[] $pagesLangs
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'trans' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'title',
                    'content'
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id'], 'required'],
            [['active', 'template_id', 'created_at', 'updated_at'], 'integer'],
            // Types
            [['type'], 'string'],
            ['type', 'in', 'range' => ['system', 'user-defined']],
            // Default type to 'user-defined'
            ['type', 'default', 'value' => 'user-defined']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'template_id' => Yii::t('app', 'Template'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PageLang::className(), ['page_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::className(), ['entity_id' => 'id'])->where(['entity' => Seo::TYPE_PAGE]);
    }
}
