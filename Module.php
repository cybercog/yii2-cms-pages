<?php

namespace infoweb\pages;

use Yii;
use yii\base\Event;
use yii\db\ActiveRecord;
use infoweb\pages\models\Page;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php'));
        
        $this->setEventHandlers();
    }
    
    public function setEventHandlers()
    {
        // Set eventhandlers for the 'Page' model
        Event::on(Page::className(), ActiveRecord::EVENT_BEFORE_DELETE, function ($event) {
            
            // Check if the page is the homepage
            if ($event->sender->homepage == 1)
                throw new \yii\base\Exception(Yii::t('infoweb/pages', 'The page can not be deleted because it is the homepage'));
            
            // Check if the page is not used in a menu
            if ($event->sender->isUsedInMenu())
                throw new \yii\base\Exception(Yii::t('infoweb/pages', 'The page can not be deleted because it is used in a menu'));
            
            // Delete the attached entities
            if (!$event->sender->deleteAttachedEntities())
                throw new \yii\base\Exception(Yii::t('infoweb/pages', 'Error while deleting the page'));
        });    
    }
}