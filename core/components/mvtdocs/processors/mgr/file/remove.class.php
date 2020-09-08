<?php
class mvtDocsFileRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'mvtDocsFiles';
    public $classKey = 'mvtDocsFiles';
    public $languageTopics = array('mvtdocs');
    public $permission = 'mvtdocs_remove';


    public function process()   {
		$this->modx->lexicon->load('core:file');
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('mvtdocs_files_err_ns'));
        }

        foreach ($ids as $id) {
			
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('mvtdocs_files_err_nf'));
            }
			
			if($object->get('itemtype') == 'file') {
				$path = $object->get('path');
				
				$source_id = $object->get('source');
			
				if(!empty($source_id)) {
					$source = $this->modx->getObject('sources.modMediaSource', $source_id);
					$source->initialize();
					if (!$source->removeObject($path)) {
						return $this->failure($this->modx->lexicon('mvtdocs_files_err_remove') . ': ' . print_r($source->getErrors(), 1));
					}
				}
				else {
					return $this->failure($this->modx->lexicon('mvtdocs_files_err_nosource'));
				}
			}
			
            $object->remove();
        }

        return $this->success();
    }

}

return 'mvtDocsFileRemoveProcessor';