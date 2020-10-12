<?php

class msafFieldUpdateProcessor extends modObjectUpdateProcessor
{
    /* @var msafField $object */
    public $object;
    public $objectType = 'msafField';
    public $classKey = 'msafField';
    public $languageTopics = array('mscml:manager');

    public function beforeSet()
    {
        $this->setCheckbox('active');
        $this->setCheckbox('show_card');
        return parent::beforeSet();
    }

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function afterSave()
    {
        return true;
    }
}

return 'msafFieldUpdateProcessor';