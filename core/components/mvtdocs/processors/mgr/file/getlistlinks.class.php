<?php

class mvtDocsLinksGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'mvtDocsResources';
    public $classKey = 'mvtDocsResources';
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

		$file_id = $this->getProperty('file_id');
        if (empty($file_id)) {
            return;
        }
		$c->leftJoin('modResource', 'Resource','mvtDocsResources.resource_id = Resource.id');
		
		$c->where(array(
            'file_id' => (int)$file_id,
			//'resource_id:<>' => $this->getProperty('resource_id')
        ));
		
		$c->select($this->modx->getSelectColumns('mvtDocsResources','mvtDocsResources'));
        $c->select($this->modx->getSelectColumns('modResource','Resource','',['pagetitle','parent']));

        return $c;
    }



    public function prepareRow(xPDOObject $object) {
        $array = $object->toArray();
		
		$array['color'] = ($this->getProperty('resource_id') == $array['resource_id']) ? 'A40004' : '333';

		$array['category'] = '';
		if($parent = $this->modx->getObject('modResource',$array['parent'])) {
			$array['category'] = $parent->get('pagetitle');
		}
		
        $array['actions'] = [];
		
		
		$array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('mvtdocs_link_remove'),
            'multiple' => $this->modx->lexicon('mvtdocs_links_remove'),
            'action' => 'removeLink',
            'button' => true,
            'menu' => true,
        ];
       
        return $array;
    }

}

return 'mvtDocsLinksGetListProcessor';