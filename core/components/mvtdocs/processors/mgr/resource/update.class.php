<?php

class mvtDocsItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'mvtDocsItem';
    public $classKey = 'mvtDocsItem';
    public $languageTopics = array('mvtdocs');
    public $permission = 'mvtdocs_save';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mvtdocs_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
            $this->modx->error->addField('name', $this->modx->lexicon('mvtdocs_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'mvtDocsItemCreateProcessor';