<?php
/** @var modX $modx */
/* @var array $scriptProperties */
switch ($modx->event->name) {
    case 'OnMODXInit':
    case 'OnHandleRequest':
        /* @var msAddField $msAddField*/
        $msAddField = $modx->getService('msaddfield', 'msAddField', $modx->getOption('msaddfield_core_path', $scriptProperties, $modx->getOption('core_path') . 'components/msaddfield/') . 'model/');
        if ($msAddField instanceof msAddField) {
            $msAddField->loadHandlerEvent($modx->event, $scriptProperties);
        }
        break;
}
return '';