<?php
$policies = [];

$policy = $modx->newObject('modAccessPolicy');
$policy->fromArray([
    'name' => 'mvtDocsManagerPolicy',
    'description' => '',
    'parent' => 0,
    'class' => '',
    'lexicon' => 'mvtdocs:permissions',
    'data' => json_encode([
        'mvtdocs_save' => true,
        'mvtdocs_view' => true,
        'mvtdocs_list' => true,
        'mvtdocs_remove' => true,
    ]),
], '', true, true);

$policies[] = $policy;

return $policies;
