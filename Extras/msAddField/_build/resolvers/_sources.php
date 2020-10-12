<?php

/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:

            //core/components/msexportordersexcel/export
            $tmp = explode('/', MODX_ASSETS_URL);
            $assets = $tmp[count($tmp) - 2];


            $properties = array(
                'name' => 'msAddField',
                'description' => 'Default source for files of msAddField',
                'class_key' => 'sources.modFileMediaSource',
                'properties' => array(
                    'basePath' => array(
                        'name' => 'basePath',
                        'desc' => 'prop_file.basePath_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => 'assets/images/msaddfield/',
                    ),
                    'basePathRelative' => array(
                        'name' => 'basePathRelative',
                        'desc' => 'prop_file.basePathRelative_desc',
                        'type' => 'combo-boolean',
                        'lexicon' => 'core:source',
                        'value' => true,
                    ),
                    'baseUrl' => array(
                        'name' => 'baseUrl',
                        'desc' => 'prop_file.baseUrl_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => 'assets/images/msaddfield/',
                    ),
                    'allowedFileTypes' => array(
                        'name' => 'allowedFileTypes',
                        'desc' => 'prop_file.allowedFileTypes_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => 'jpg,jpeg,png,gif',
                    ),
                    'thumbnails' => array(
                        'name' => 'thumbnails',
                        'desc' => 'prop_file.thumbnails_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => '{"small":{"w":120,"h":90,"q":90,"zc":"1","bg":"000000"}}',
                    ),
                    'thumbnailType' => array(
                        'name' => 'thumbnailType',
                        'desc' => 'prop_file.thumbnailType_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => 'Jpg',
                    ),
                    'thumbnailQuality' => array(
                        'name' => 'thumbnailQuality',
                        'desc' => 'prop_file.thumbnailQuality_desc',
                        'type' => 'numberfield',
                        'lexicon' => 'core:source',
                        'value' => 90,
                    ),
                    'upload_files' => array(
                        'name' => 'upload_files',
                        'desc' => 'prop_file.upload_files_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => 'xls,xlsx,json,csv',
                    ),
                    'imageExtensions' => array(
                        'name' => 'imageExtensions',
                        'desc' => 'prop_file.imageExtensions_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => 'jpg,jpeg,png,gif',
                    ),
                    'skipFiles' => array(
                        'name' => 'skipFiles',
                        'desc' => 'prop_file.skipFiles_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => '.placeholder,.svn,.git,_notes,nbproject,.idea,.DS_Store',
                    ),
                    'maxUploadWidth' => array(
                        'name' => 'maxUploadWidth',
                        'desc' => 'prop_file.maxUploadWidth_desc',
                        'type' => 'numberfield',
                        'lexicon' => 'core:source',
                        'value' => 1920,
                    ),
                    'maxUploadHeight' => array(
                        'name' => 'maxUploadHeight',
                        'desc' => 'prop_file.maxUploadHeight_desc',
                        'type' => 'numberfield',
                        'lexicon' => 'core:source',
                        'value' => 1080,
                    ),
                    'maxUploadSize' => array(
                        'name' => 'maxUploadSize',
                        'desc' => 'prop_file.maxUploadSize_desc',
                        'type' => 'numberfield',
                        'lexicon' => 'core:source',
                        'value' => 10485760,
                    ),
                    'imageNameType' => array(
                        'name' => 'imageNameType',
                        'desc' => 'prop_file.imageNameType_desc',
                        'type' => 'textfield',
                        'lexicon' => 'core:source',
                        'value' => 'friendly',
                    ),
                ),
                'is_stream' => 1,
            );
            /** @var $source modMediaSource */
            if (!$source = $modx->getObject('sources.modMediaSource', array('name' => $properties['name']))) {
                $source = $modx->newObject('sources.modMediaSource', $properties);
            } else {
                $default = $source->get('properties');
                foreach ($properties['properties'] as $k => $v) {
                    if (!array_key_exists($k, $default)) {
                        $default[$k] = $v;
                    }
                }
                $source->set('properties', $default);
            }
            $source->save();

            if ($setting = $modx->getObject('modSystemSetting', array('key' => 'msaddfield_source_default'))) {
                if (!$setting->get('value')) {
                    $setting->set('value', $source->get('id'));
                    $setting->save();
                }
            }

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;