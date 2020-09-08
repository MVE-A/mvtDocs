<?php
class mvtDocs {

    public $modx;

    function __construct(modX &$modx, array $config = array())   {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('mvtdocs_core_path', $config,
            $this->modx->getOption('core_path') . 'components/mvtdocs/'
        );
        $assetsUrl = $this->modx->getOption('mvtdocs_assets_url', $config,
            $this->modx->getOption('assets_url') . 'components/mvtdocs/'
        );
        $connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
        ), $config);

        $this->modx->addPackage('mvtdocs', $this->config['modelPath']);
        $this->modx->lexicon->load('mvtdocs:default');
    }



	public function getIncludedCategories ($id) {
		$catlist = [];
		$childs = $this->modx->getChildIds($id,50,['context' => 'web']);
		if(count($childs) > 0) {
			$q = $this->modx->newQuery('modResource', [
				'id:IN' => $childs,
			]);
			$q->where([
				['class_key' => 'msCategory'], 
				['isfolder' => 1]
			],xPDOQuery::SQL_OR);
					
			$l = $this->modx->getIterator('modResource',$q);
			foreach($l as $i) {
				$catlist[] = $i->get('id');
			}
			if(count($catlist) > 0) {
				return $catlist;
			}
		}
		
		return false;
	}
	
	
	
	public function getRelatedProducts ($id) {
		$ms2_path = $this->modx->getOption('core_path').'components/minishop2/model/minishop2/';
        if(is_dir($ms2_path)) {
			$miniShop2 = $this->modx->getService('miniShop2');
			if ($miniShop2 instanceof miniShop2) {
				$related = [];
				$where = ['master' => $id];
				$link_ids = explode(',',$this->modx->getOption('mvtdocs_links_ids'));
				if(count($link_ids) != 0) {
					$where['link:IN'] = $link_ids;
				}
				
				$q = $this->modx->newQuery('msProductLink', $where);
				$l = $this->modx->getIterator('msProductLink',$q);
				foreach($l as $i) {
					$p = $i->get('slave');
					if(!in_array($p,$related)) {
						$related[] = $p;
					}
				}
				if(count($related) > 0) {
					return $related;
				}
			}
		}
		return false;
	}
	
	
}