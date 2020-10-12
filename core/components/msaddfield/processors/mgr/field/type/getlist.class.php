<?php

class msafFieldTypeGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'msafField';
    public $classKey = 'msafField';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = ['mscml:manager'];
    //public $permission = 'list';

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        /* @var msafField $object */
        $object = $this->modx->newObject('msafField');

        $meta = array_keys($object->metaData);

        $list = array();
        foreach ($object->metaData as $field => $meta) {
            $list[] = array(
                'id' => $field,
                'name' => $meta['title']
            );
        }

       /* $list = array(
            0 => array(
                'id' => 'price',
                'name' => 'price'
            ),
            1 => array(
                'id' => 'storage',
                'name' => 'storage'
            ),
            2 => array(
                'id' => 'numberfield',
                'name' => 'numberfield'
            ),
            3 => array(
                'id' => 'textfield',
                'name' => 'textfield'
            ),
            4 => array(
                'id' => 'xcheckbox',
                'name' => 'xcheckbox'
            )
        );*/

        return $this->outputArray($list, count($list));
    }

}

return 'msafFieldTypeGetListProcessor';