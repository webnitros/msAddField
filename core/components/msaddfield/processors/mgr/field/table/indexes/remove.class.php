<?php

class msafFieldTableIndexesProcessor extends modObjectProcessor
{
    public $objectType = 'msafField';
    public $classKey = 'msafField';
    public $languageTopics = ['mscml:manager'];
    public $permission = 'remove';

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $id = (int)$this->getProperty('id');
        if (empty($id)) {
            return $this->failure('Передан пустой ID');
        }

        /** @var msafField $object */
        if (!$object = $this->modx->getObject($this->classKey, $id)) {
            return $this->failure($this->modx->lexicon('modextra_item_err_nf'));
        }


        if (!$object->hasField()) {
            return $this->failure('Поле отсутствует в базе');
        }


        try {
            $object->removeIndex();
        } catch (Exception $e) {
            $this->failure($e->getMessage());
        }
        return $this->success();
    }

}

return 'msafFieldTableIndexesProcessor';