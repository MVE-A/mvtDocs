<?php
class mvtDocsLinkRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'mvtDocsResources';
    public $classKey = 'mvtDocsResources';
    public $languageTopics = array('mvtdocs');
    public $permission = 'mvtdocs_remove';


    public function process()   {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('mvtdocs_links_err_ns'));
        }

        foreach ($ids as $id) {
			
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('mvtdocs_links_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'mvtDocsLinkRemoveProcessor';