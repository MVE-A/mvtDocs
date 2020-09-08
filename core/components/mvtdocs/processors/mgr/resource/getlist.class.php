<?php

class mvtDocsItemGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'modResource';
    public $classKey = 'modResource';
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
        $query = trim($this->getProperty('query'));
		$category = trim($this->getProperty('category'));
		
		$cat = (int)$this->modx->getOption('mvtdocs_category');
		if(!empty($cat)) {
			$mvtDocs = $this->modx->getService('mvtdocs');
			if($ic = $mvtDocs->getIncludedCategories($cat)) { 
				$c->where(['parent:IN' => array_merge([$cat],$ic)]]);
			}
			else {
				$c->where(['id:<' => 0]);
			}
		}
		
		if (!empty($category)) {
			$c->where(['parent' => $category]);
        }
		
		if (!empty($query)) {
			$ms2_path = $this->modx->getOption('core_path').'components/minishop2/model/minishop2/';
			if(is_dir($ms2_path)) {
				$miniShop2 = $this->modx->getService('miniShop2');
				if ($miniShop2 instanceof miniShop2) {
					$c->leftJoin('msProductData', 'Data', 'Data.id = modResource.id');
					$c->where([
						['modResource.pagetitle:LIKE' => "%{$query}%"], 
						['modResource.id' => $query], 
						['Data.article:LIKE' => "%{$query}%"]
					],xPDOQuery::SQL_OR); 
				}
			}
			else {
				$c->where([
					['pagetitle:LIKE' => "%{$query}%"], 
					['id' => $query]
				],xPDOQuery::SQL_OR);
			}
        }
		
		

        return $c;
    }



    public function prepareRow(xPDOObject $object) {
        $array = $object->toArray();

		$array['files'] = $this->modx->query("SELECT COUNT(*) FROM {$this->modx->getTableName('mvtDocsResources')} WHERE resource_id={$array['id']}")->fetchColumn();
		
		$array['color'] = ($array['files'] > 0) ? '00CC00' : '333';
		
		$parents_ = [];
		$parents = $this->modx->getParentIds($array['id'], 5, ['context' => $array['context_key'],'class_key:NOT IN' => ['msProduct']]);
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
			
				
		$array['category'] = '';
		if(!empty($parents_)) {
			foreach ($parents_ as $parent) {
				$array['category'] .= /*'['.$parent['id'].'] '.*/$parent['pagetitle'].'/';
			}
		}
		
		if(!empty($array['article'])) {
			$array['pagetitle'] = '<small>['.$array['article'].']</small> '.$array['pagetitle'];
		}
		
        $array['actions'] = array();
		
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('mvtdocs_item_update'),
            'action' => 'updateItem',
            'button' => true,
            'menu' => true,
        );

        /*if (!$array['active']) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('mvtdocs_item_enable'),
                'multiple' => $this->modx->lexicon('mvtdocs_items_enable'),
                'action' => 'enableItem',
                'button' => true,
                'menu' => true,
            );
        } else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('mvtdocs_item_disable'),
                'multiple' => $this->modx->lexicon('mvtdocs_items_disable'),
                'action' => 'disableItem',
                'button' => true,
                'menu' => true,
            );
        }*/

       
        return $array;
    }

}

return 'mvtDocsItemGetListProcessor';