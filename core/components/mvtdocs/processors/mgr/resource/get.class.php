<?php

class mvtDocsItemGetProcessor extends modProcessor {

    public $permission = 'view';

	public function process() {
		
		if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }
		
		$response = [];
		
		$resource_id = $this->getProperty('resource_id');
		//$types = explode(',',$this->modx->getOption('mvtdocs_document_types'));
		
		$response = [
			'resource_id' => $resource_id,
			//'types' => $types
		];
		
		
		return $this->success('', $response);
	}

}

return 'mvtDocsItemGetProcessor';
