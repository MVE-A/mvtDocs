<?php
if (!$mvtDocs = $modx->getService('mvtdocs', 'mvtDocs', $modx->getOption('mvtdocs_core_path', null,
        $modx->getOption('core_path') . 'components/mvtdocs/') . 'model/mvtdocs/', $scriptProperties)
) {
    return 'Could not load mvtDocs class!';
}

$pdoTools = $modx->getService('pdoTools');

$resource = $modx->getOption('resource', $scriptProperties, $modx->resource->id, true);
$limit = $modx->getOption('limit', $scriptProperties, 5);
$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.mvtDocsList');
$sortby = $modx->getOption('sortby', $scriptProperties, 'name');
$sortdir = $modx->getOption('sortbir', $scriptProperties, 'ASC');
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, "\n");

$c = $modx->newQuery('mvtDocsFiles');
$c->setClassAlias('File');
$c->leftJoin('mvtDocsResources','Resource','Resource.file_id = File.id');
$c->where(['Resource.resource_id' => $resource]);
if(!empty($where)) {
    $c->where(json_decode($where,1));
}
$c->select(['Resource.*','File.*']);
$c->sortby($sortby, $sortdir);
$c->limit($limit);


$items = $modx->getIterator('mvtDocsFiles', $c);

$docs = [];
foreach ($items as $item) {
    $data = $item->toArray();
    $data['ext'] = '';
    
    switch($data['itemtype']) {
        case 'file':
            $data['ext'] = array_pop(explode('.',$data['file']));
            break;
        case 'link':
            if(preg_match('/youtube/',$data['url'])) {
                $data['ext'] = 'video';
            }
            break;
    }
    $docs[$data['type']][] = $data;
}

return $pdoTools->getChunk($tpl, ['docs' => $docs]);
  
    
/*
$list = [];
foreach ($items as $item) {
    $data = $item->toArray();
    $data['ext'] = array_pop(explode('.',$data['file']));
    $list[] = $pdoTools->getChunk($tpl, $data);
}
$output = implode($outputSeparator, $list);
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}
return $output;*/