<?php


class modmsAddFieldStorageGetListProcessor extends modObjectProcessor
{
    public $languageTopics = ['msaddfield:manager'];

    /** {@inheritDoc} */
    public function process()
    {
        $array = array();
        $q = $this->modx->newQuery('msixDisplayArea');
        $q->select('id as value,name');
        $q->sortby('id', 'ASC');

        $count = $this->modx->getCount('msixDisplayArea', $q);
        $q->limit($this->getProperty('limit'), $this->getProperty('start'));

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $q->where(array(
                'name:LIKE' => "%{$query}%",
                #'OR:code' => "%{$query}%"
            ));
        }

        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $array[] = $row;
            }
        }
        return $this->outputArray($array, $count);
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive(array(
                array(
                    'name' => 'Все области',
                    'value' => '',
                )
            ), $array);
        }

        return parent::outputArray($array, $count);
    }

}

return 'modmsAddFieldStorageGetListProcessor';