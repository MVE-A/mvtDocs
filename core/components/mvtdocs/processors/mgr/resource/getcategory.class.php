<?php
class mvtDocsGetCategoryGetListProcessor extends modObjectGetListProcessor {

	public $objectType = 'modResource';
    public $classKey = 'modResource';
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $permission = 'mvtdocs_list';


    public function beforeQuery()   {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
	
	
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = trim($this->getProperty('query'));
		
		$c->where([
			['class_key' => 'msCategory'], 
			['isfolder' => 1]
		],xPDOQuery::SQL_OR);
		
		
		$cat = (int)$this->modx->getOption('mvtdocs_category');
		if(!empty($cat)) {
			$mvtDocs = $this->modx->getService('mvtdocs');
			if($ic = $mvtDocs->getIncludedCategories($cat)) { 
				$c->where(['id:IN' => $ic]);
			}
			else {
				$c->where(['id:<' => 0]);
			}
		}
		
		if (!empty($query)) {
			$c->where(['pagetitle:LIKE' => "%{$query}%"]);
        }
		
        return $c;
    }
	
	
	public function prepareRow(xPDOObject $object) {
        $array = $object->toArray();

		$parents_ = [];
		$parents = $this->modx->getParentIds($array['id'], 2, ['context' => $array['context_key']]);
        if (empty($parents[count($parents) - 1])) {
            unset($parents[count($parents) - 1]);
        }
        if (!empty($parents) && is_array($parents)) {
            $q = $this->modx->newQuery('modResource', array('id:IN' => $parents));
            $q->select('id,pagetitle');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $key = array_search($row['id'], $parents);
                    if ($key !== false) {
                        $parents[$key] = $row;
                    }
                }
            }
            $parents_ = array_reverse($parents);
        }
		
		
		$array_ = [
			'id' => $array['id'],
			'parents' => $parents_,
			'pagetitle' => $array['pagetitle']
		];

        return $array_;
    }

}
return 'mvtDocsGetCategoryGetListProcessor';
?>