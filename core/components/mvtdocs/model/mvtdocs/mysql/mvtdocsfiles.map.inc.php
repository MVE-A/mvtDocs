<?php
$xpdo_meta_map['mvtDocsFiles']= array (
  'package' => 'mvtdocs',
  'version' => '1.1',
  'table' => 'mvtdocs_files',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'name' => '',
    'description' => '',
    'file' => '',
    'path' => '',
    'url' => '',
    'source' => NULL,
    'added' => NULL,
    'itemtype' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '300',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '300',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'file' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'path' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '600',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '600',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'source' => 
    array (
      'dbtype' => 'int',
      'precision' => '2',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'added' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
    ),
    'itemtype' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'composites' => 
  array (
    'Resources' => 
    array (
      'class' => 'mvtDocsResources',
      'local' => 'id',
      'foreign' => 'file_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
