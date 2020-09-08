<?php
$snippets = [];

$tmp = array(
    'mvtDocs' => [
        'file' => 'mvtdocs',
        'description' => '',
    ],
);

foreach ($tmp as $k => $v) {
    $snippet = $modx->newObject('modSnippet');
    $snippet->fromArray([
        'id' => 0,
        'name' => $k,
        'description' => @$v['description'],
        'snippet' => getSnippetContent($sources['source_core'] . '/elements/snippets/snippet.' . $v['file'] . '.php'),
        'static' => BUILD_SNIPPET_STATIC,
        'source' => 1,
        'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/snippets/snippet.' . $v['file'] . '.php',
    ], '', true, true);

    $properties = include $sources['build'] . 'properties/properties.' . $v['file'] . '.php';
    $snippet->setProperties($properties);

    $snippets[] = $snippet;
}
unset($tmp, $properties);

return $snippets;