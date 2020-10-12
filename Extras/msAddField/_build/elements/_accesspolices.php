<?php
return [
    'msExportOrdersExcel' => [
        'name' => 'msExportOrdersExcelMedia',
        'templateName' => 'msExportOrdersExcelTemplate',
        'description' => 'The security policy for a msExportOrdersExcel',
        'parent' => 0,
        'class' => '',
        'lexicon' => 'permissions',
        'data' => json_encode(array(
            'file_view' => true
        ))
    ]
];