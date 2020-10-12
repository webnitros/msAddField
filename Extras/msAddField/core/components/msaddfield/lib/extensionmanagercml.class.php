<?php


class extensionManagerCml
{
    /* @var modX|null $modx */
    protected $modx = null;
    private $metaTree = array(
        'modResource' => array(
            'fieldMeta' => array(
            ),
            'indexes' => array(
            )
        ),
    );

    /**
     * msCmlGenerateMapFields constructor.
     * @param modX $modx
     */
    function __construct(modX &$modx)
    {
        $this->modx = $modx;
    }

    /**
     * @param $class
     * @return bool|array
     */
    private function getMeta($class)
    {
        if (isset($this->metaTree[$class])) {
            return $this->metaTree[$class];
        }
        return false;
    }

    /**
     * @param $class
     * @return bool|array
     */
    public function classExtension($class)
    {
        if ($meta = $this->getMeta($class)) {
            $this->modx->loadClass($class);
            if (isset($meta['fieldMeta']) and count($meta['fieldMeta']) > 0) {
                foreach ($meta['fieldMeta'] as $field => $options) {
                    if (!isset($this->modx->map[$class]['fields'][$field])) {
                        $this->modx->map[$class]['fields'][$field] = '';
                        $this->modx->map[$class]['fieldMeta'][$field] = $options;
                    }
                }
            }
            if (isset($meta['indexes']) and count($meta['indexes']) > 0) {
                foreach ($meta['indexes'] as $field => $options) {
                    if (!isset($this->modx->map[$class]['indexes'][$field])) {
                        $this->modx->map[$class]['indexes'][$field] = $options;
                    }
                }
            }
        }
        return false;
    }

}