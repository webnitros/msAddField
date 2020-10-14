<?php

class msafFieldGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'msafField';
    public $classKey = 'msafField';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = ['msaddfield:manager'];
    //public $permission = 'list';

    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }
        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where([
                'name:LIKE' => "%{$query}%",
            ]);
        }
        return $c;
    }

    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        /* @var msafField $object */
        $array = $object->toArray();
        $array['actions'] = [];


        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('msaddfield_field_update'),
            'action' => 'updateField',
            'button' => true,
            'menu' => true,
        ];

        $array['actions'][] = '-';
        $array['has_index'] = $object->hasIndex();

        $array['create_base'] = $object->hasField();
        if (!$array['create_base']) {
            // _table_create
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-refresh',
                'title' => $this->modx->lexicon('msaddfield_action_updatetable'),
                'action' => '_table_create',
                'button' => false,
                'menu' => true,
            ];
        } else {
            // _table_remove
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-remove',
                'title' => $this->modx->lexicon('msaddfield_action_removetable'),
                'action' => '_table_remove',
                'button' => false,
                'menu' => true,
            ];


            if ($object->allowedIndexed()) {
                $array['actions'][] = '-';
                if (!$array['has_index']) {
                    // add indexes
                    $array['actions'][] = [
                        'cls' => '',
                        'icon' => 'icon icon-refresh',
                        'title' => $this->modx->lexicon('msaddfield_action_add_indexes'),
                        'action' => 'addIndexesField',
                        'button' => false,
                        'menu' => true,
                    ];
                } else {
                    // remove indexes
                    $array['actions'][] = [
                        'cls' => '',
                        'icon' => 'icon icon-trash-o action-red',
                        'title' => $this->modx->lexicon('msaddfield_action_remove_indexes'),
                        'action' => 'removeIndexesField',
                        'button' => false,
                        'menu' => true,
                    ];
                }
            }

        }


        $array['actions'][] = '-';
        // Extension
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-refresh',
            'title' => $this->modx->lexicon('msaddfield_action_extension'),
            'action' => 'extensionField',
            'button' => false,
            'menu' => true,
        ];


        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('msaddfield_action_remove'),
            'action' => 'removeField',
            'button' => false,
            'menu' => true,
        ];


        return $array;
    }

}

return 'msafFieldGetListProcessor';