<?php

/**
 * BaseSlStoreStatus
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property Doctrine_Collection $SlStore
 * 
 * @method string              getName()    Returns the current record's "name" value
 * @method Doctrine_Collection getSlStore() Returns the current record's "SlStore" collection
 * @method SlStoreStatus       setName()    Sets the current record's "name" value
 * @method SlStoreStatus       setSlStore() Sets the current record's "SlStore" collection
 * 
 * @package    collections
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSlStoreStatus extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sl_store_status');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('SlStore', array(
             'local' => 'id',
             'foreign' => 'store_status_id'));
    }
}