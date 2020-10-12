<?php

class msafFieldRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'msafField';
    public $classKey = 'msafField';
    public $languageTopics = ['mscml:manager'];
    //public $permission = 'remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('modextra_item_err_ns'));
        }

        /** @var msafField $object */
        if (!$object = $this->modx->getObject($this->classKey, $id)) {
            return $this->failure($this->modx->lexicon('modextra_item_err_nf'));
        }

        try {
            $object->removeField();
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
        $object->remove();
        return $this->success();
    }

}

return 'msafFieldRemoveProcessor';