<?php

namespace infoweb\pages\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pages_lang".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $language
 * @property string $title
 * @property string $content
 *
 * @property Pages $page
 */
class PageLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages_lang';
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
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
            // Required
            [['language', 'name'], 'required'],
            // Only required for existing records
            [['page_id'], 'required', 'when' => function($model) {
                return !$model->isNewRecord;
            }],
            // Only required for the app language
            [['content'], 'required', 'when' => function($model) {
                return $model->language == Yii::$app->language;
            }],
            // Trim
            [['name', 'title', 'content'], 'trim'],
            // Types
            [['page_id', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['language'], 'string', 'max' => 2],
            [['name', 'title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('infoweb/pages', 'Page ID'),
            'language' => Yii::t('app', 'Language'),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
