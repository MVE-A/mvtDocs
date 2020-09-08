<?php
class mvtDocsLinkAddProcessor extends modObjectCreateProcessor {
    
	public $objectType = 'mvtDocsResources';
    public $classKey = 'mvtDocsResources';
    public $languageTopics = array('mvtdocs');
    public $permission = 'mvtdocs_save';


	public function beforeSave() {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
	
	
	public function process() {
		
		$resource_id = trim($this->getProperty('resource_id'));
		$file_id = trim($this->getProperty('file_id'));
		$resource_from = trim($this->getProperty('resource_from'));
		if (empty($resource_id) || empty($file_id) || empty($resource_from)) {
			return $this->failure($this->modx->lexicon('mvtdocs_links_err_nf'));
        }
		
		if($lk = $this->modx->getObject('mvtDocsResources',[
			'resource_id' => $resource_id,
			'file_id' => $file_id
			])) {
			return $this->failure($this->modx->lexicon('mvtdocs_links_err_is'));
		}
		
		if(!$mf = $this->modx->getObject('mvtDocsResources',['resource_id' => $resource_from])) {
			return $this->failure($this->modx->lexicon('mvtdocs_links_err_nf'));
		}
		else {
			$this->setProperty('type',$mf->get('type'));
		}
		
		return parent::process();
	}

}
return 'mvtDocsLinkAddProcessor';