<?php

class mvtDocsFileAddProcessor extends modObjectCreateProcessor {
    
	public $objectType = 'mvtDocsFiles';
    public $classKey = 'mvtDocsFiles';
    public $languageTopics = array('mvtdocs');
    public $permission = 'mvtdocs_save';


	public function beforeSave() {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
	

    public function beforeSet() {
		        
		$name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mvtdocs_files_err_name'));
        }


        return parent::beforeSet();
    }
	
	
	
	public function process() {
		$this->modx->lexicon->load('core:file');
		
		$resource_id = $this->getProperty('resource_id');
		if(empty($resource_id)) {
			return $this->failure($this->modx->lexicon('mvtdocs_files_err_save'));
		}
		
		if($this->getProperty('itemtype') == 'file') {
			
			if(!$_FILES['loader']['tmp_name']) {
				$this->modx->error->addField('file1', $this->modx->lexicon('mvtdocs_files_err_path'));
			}
		
			$source_id = $this->modx->getOption('mvtdocs_source');
		
			if(!empty($source_id)) {
				$source = $this->modx->getObject('sources.modMediaSource', $source_id);
				$source->initialize();
				$properties = $source->getProperties();
				
				$dir = $resource_id.'/';
				$path = $this->modx->getOption('base_path').ltrim($properties['basePath']['value'],'/').$dir;
				if (!file_exists($path)) {
					mkdir($path);
				}
				
				$fd = explode('.',$_FILES['loader']['name']);
				$ext = array_pop($fd);
				$filename = '';
				
				$k = 0;
				foreach ($fd as $i) {
					$k++;
					if($k <= count($fd)) {
					   $filename .= $i;
					}
				}

				if($this->modx->getOption('mvtdocs_filename_translit')) {
					$resource = new modResource($this->modx);
					$filename = $resource->cleanAlias($filename);
				}
				
				$_FILES['loader']['name'] = $filename.'.'.$ext;
				
				$pathtosave = $dir.$_FILES['loader']['name'];
				
				if($lk = $this->modx->getObject('mvtDocsFiles',['path' => $pathtosave])) {
					return $this->failure($this->modx->lexicon('mvtdocs_files_err_is'));
				}
				
				
				if (!$source->uploadObjectsToContainer($dir, $_FILES)) {
					return $this->failure($this->modx->lexicon('mvtdocs_files_err_save') . ': ' . print_r($source->getErrors(), 1));
				}
				
				$url = $source->getObjectUrl($pathtosave);
				
				$this->setProperty('url', $url);
				$this->setProperty('file', $_FILES['loader']['name']);
				$this->setProperty('source', $source_id);
				$this->setProperty('path', $pathtosave);
			}
			else {
				return $this->failure($this->modx->lexicon('mvtdocs_files_err_nosource'));
			}
		}
		else {
			$extlink = trim($this->getProperty('extlink'));
			if (empty($extlink)) {
				$this->modx->error->addField('extlink', $this->modx->lexicon('mvtdocs_file_err_extlink'));
			}
			$this->setProperty('url', $extlink);
			$this->setProperty('file', $extlink);
			$this->setProperty('source', 0);
			$this->setProperty('path', $extlink);
		}
		
		$this->setProperty('added', time());

		return parent::process();
	}
	
	
	
	
	public function afterSave() { 
		$data = $this->object->toArray();

		$res = $this->modx->newObject('mvtDocsResources');
		$res->fromArray([
			'resource_id' => $data['resource_id'],
			'file_id' => $data['id'],
			'type' => $data['type'],
		]);
		$res->save();
		
		if($this->modx->getOption('mvtdocs_auto_links')) {
			$mvtDocs = $this->modx->getService('mvtdocs');
			if($rp = $mvtDocs->getRelatedProducts($data['resource_id'])) { 
				foreach($rp as $item) {
					if(!$df = $this->modx->getObject('mvtDocsResources',[
						'resource_id' => $item,
						'file_id' => $data['id']
					])) {
						$res = $this->modx->newObject('mvtDocsResources');
						$res->fromArray([
							'resource_id' => $item,
							'file_id' => $data['id'],
							'type' => $data['type'],
						]);
						$res->save();
					}
				}
			}
		}
		
		return true; 
	}

}

return 'mvtDocsFileAddProcessor';