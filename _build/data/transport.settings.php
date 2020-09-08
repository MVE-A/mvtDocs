<?php
$settings = [];

$tmp = [
    'document_types' => [
        'xtype' => 'textarea',
        'value' => '',
        'area' => 'mvtdocs_main',
    ],
	'category' => array(
        'value' => '',
        'xtype' => 'textarea',
        'area' => 'mvtdocs_main',
    ),
	'source' => array(
        'value' => 1,
        'xtype' => 'modx-combo-source',
        'area' => 'mvtdocs_main',
    ),
	'filename_translit' => array(
        'value' => false,
        'xtype' => 'combo-boolean',
        'area' => 'mvtdocs_main',
    ),
	'auto_links' => array(
        'value' => false,
        'xtype' => 'combo-boolean',
        'area' => 'mvtdocs_main',
    ),
	'links_ids' => array(
        'value' => '',
        'xtype' => 'textarea',
        'area' => 'mvtdocs_main',
    ),
];

foreach ($tmp as $k => $v) {
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        [
            'key' => 'mvtdocs_' . $k,
            'namespace' => PKG_NAME_LOWER,
        ], $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
