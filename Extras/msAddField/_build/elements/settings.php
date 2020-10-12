<?php

return [
    /*'combo_boolean' => [
        'xtype' => 'combo-boolean',
        'value' => true,
        'area' => 'msaddfield_main',
    ],*/
    'plugins_core_path_index' => [
        'xtype' => 'textfield',
        'value' => '{core_path}components/minishop2/plugins/msaddfield/index.php',
        'area' => 'msaddfield_main',
    ],
    'plugins_core_path' => [
        'xtype' => 'textfield',
        'value' => '{core_path}components/minishop2/plugins/msaddfield/msproductdata.inc.php',
        'area' => 'msaddfield_main',
    ],
    'plugins_assets_path' => [
        'xtype' => 'textfield',
        'value' => '{assets_path}components/minishop2/plugins/msaddfield/msproductdata.js',
        'area' => 'msaddfield_main',
    ],
];