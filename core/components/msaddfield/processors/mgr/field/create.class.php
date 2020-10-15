<?php

class msafFieldCreateProcessor extends modObjectCreateProcessor
{
    /* @var msafField $object */
    public $object;
    public $objectType = 'msafField';
    public $classKey = 'msafField';
    public $languageTopics = ['mscml:manager'];

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function beforeSet()
    {

        $this->setCheckbox('active');
        $this->setCheckbox('show_card');
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mscml_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('mscml_err_ae'));
        }
        $this->setProperty('name', $name);


       /* $alias_import = trim($this->getProperty('alias_import'));
        if (empty($alias_import)) {
            $this->modx->error->addField('alias_import', $this->modx->lexicon('mscml_err_alias_import'));
        } elseif ($this->modx->getCount($this->classKey, ['alias_import' => $alias_import])) {
            $this->modx->error->addField('alias_import', $this->modx->lexicon('mscml_err_ae'));
        }
        $this->setProperty('alias_import', $alias_import);*/


        if (!preg_match("/[a-z0-9_]/i", $name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mscml_err_rus'));
        }


        if (strripos($name, ' ') !== false) {
            $this->modx->error->addField('name', $this->modx->lexicon('mscml_err_space'));
        }


        $first = (int)$name[0];
        if (!empty($first)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mscml_err_int'));
        }

        $type = trim($this->getProperty('type'));
        if (empty($type)) {
            $this->modx->error->addField('type', $this->modx->lexicon('mscml_err_type'));
        }

        $this->setProperty('name', strtolower($name));
        return parent::beforeSet();
    }

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function afterSave()
    {
        if ($this->setCheckbox('сreate_in_base')) {
            $this->object->addField();
        }
        return true;
    }

}

return 'msafFieldCreateProcessor';