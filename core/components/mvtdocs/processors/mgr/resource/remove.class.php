<?php

class mvtDocsItemRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'mvtDocsItem';
    public $classKey = 'mvtDocsItem';
    public $languageTopics = array('mvtdocs');
    public $permission = 'mvtdocs_remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('mvtdocs_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var mvtDocsItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('mvtdocs_item_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'mvtDocsItemRemoveProcessor';