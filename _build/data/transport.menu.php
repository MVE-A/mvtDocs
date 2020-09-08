<?php
$menus = [];

$tmp = [
    'mvtdocs' => [
        'description' => 'mvtdocs_menu_desc',
        'action' => 'home',
    ],
];

foreach ($tmp as $k => $v) {
    $menu = $modx->newObject('modMenu');
    $menu->fromArray(array_merge(array(
        'text' => $k,
        'parent' => 'components',
        'namespace' => PKG_NAME_LOWER,
        'icon' => '',
        'menuindex' => 0,
        'params' => '',
        'handler' => '',
    ), $v), '', true, true);
    $menus[] = $menu;
}
unset($menu, $i);

return $menus;