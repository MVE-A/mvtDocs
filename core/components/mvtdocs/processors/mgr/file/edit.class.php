<?php

class  mvtDocsFileUpdateProcessor extends modObjectUpdateProcessor {

    public $objectType = 'mvtDocsFiles';
    public $classKey = 'mvtDocsFiles';
    public $permission = 'mvtdocs_save';


    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mvtdocs_files_err_name'));
        }
        return parent::beforeSet();
    }
	
	
	
	public function afterSave()   {
		
		$file_id = (int)($this->getProperty('id'));
        $type = $this->getProperty('type');
		$resource_id = (int)$this->getProperty('resource_id');
		
		if (!empty($type)) {
			$q = $this->modx->prepare("UPDATE {$this->modx->getOption('table_prefix')}mvtdocs_resources SET type=? WHERE file_id=? AND resource_id=?");
			$q->execute([$type,$file_id,$resource_id]);
		}

		return parent::afterSave();
    }


}

return 'mvtDocsFileUpdateProcessor';
