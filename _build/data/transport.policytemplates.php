<?php
$templates = [];

$tmp = array(
    'mvtDocsManagerPolicyTemplate' => array(
        'description' => 'A policy for mvtdocs.',
        'template_group' => 1,
        'permissions' => array(
            'mvtdocs_save' => array(),
            'mvtdocs_view' => array(),
            'mvtdocs_list' => array(),
            'mvtdocs_remove' => array()
        ),
    ),
);

foreach ($tmp as $k => $v) {
    $permissions = array();

    if (isset($v['permissions']) && is_array($v['permissions'])) {
        foreach ($v['permissions'] as $k2 => $v2) {
            $permission = $modx->newObject('modAccessPermission');
            $permission->fromArray(array_merge(array(
                    'name' => $k2,
                    'description' => $k2,
                    'value' => true,
                ), $v2)
                , '', true, true);
            $permissions[] = $permission;
        }
    }

    $template = $modx->newObject('modAccessPolicyTemplate');
    $template->fromArray(array_merge(array(
            'name' => $k,
            'lexicon' => PKG_NAME_LOWER . ':permissions',
        ), $v)
        , '', true, true);

    if (!empty($permissions)) {
        $template->addMany($permissions);
    }
    $templates[] = $template;
}

return $templates;