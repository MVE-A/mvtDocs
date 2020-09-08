<?php

class mvtDocsFilesGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'mvtDocsFiles';
    public $classKey = 'mvtDocsFiles';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $permission = 'mvtdocs_list';


    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }



    public function prepareQueryBeforeCount(xPDOQuery $c) {

		$resource_id = $this->getProperty('resource_id');
        if (empty($resource_id)) {
            return;
        }
		
		$c->innerJoin('mvtDocsResources', 'Resources');
		
		$c->where(array(
            'Resources.resource_id' => (int)$resource_id
        ));
		
		$c->select(
            $this->modx->getSelectColumns('mvtDocsFiles', 'mvtDocsFiles', '', array('name', 'path', 'added'), true) . ',
            Resources.type as type'
        );

        return $c;
    }



    public function prepareRow(xPDOObject $object) {
        $array = $object->toArray();
		
		//$this->modx->log(1,print_r($array,1));
		
		$date = date_create($array['added']);
		$array['added'] = date_format($date, 'd.m.Y H:i');
		
        $array['actions'] = [];
		
		$array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('mvtdocs_file_edit'),
            'action' => 'editFile',
            'button' => true,
            'menu' => true,
        ];
		
		$array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-link',
            'title' => $this->modx->lexicon('mvtdocs_file_link'),
            'action' => 'setLinks',
            'button' => true,
            'menu' => true,
        ];
		
		$array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('mvtdocs_file_remove'),
            'multiple' => $this->modx->lexicon('mvtdocs_files_remove'),
            'action' => 'removeFile',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'mvtDocsFilesGetListProcessor';