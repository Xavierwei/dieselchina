<?php

/**
 * Stores filter form base class.
 *
 * @package    collections
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStoresFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'brand'            => new sfWidgetFormFilterInput(),
      'type'             => new sfWidgetFormFilterInput(),
      'model'            => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
      'area'             => new sfWidgetFormFilterInput(),
      'country'          => new sfWidgetFormFilterInput(),
      'city'             => new sfWidgetFormFilterInput(),
      'name'             => new sfWidgetFormFilterInput(),
      'address'          => new sfWidgetFormFilterInput(),
      'zip'              => new sfWidgetFormFilterInput(),
      'telf'             => new sfWidgetFormFilterInput(),
      'last_update'      => new sfWidgetFormFilterInput(),
      'product_line'     => new sfWidgetFormFilterInput(),
      'latitude'         => new sfWidgetFormFilterInput(),
      'longitude'        => new sfWidgetFormFilterInput(),
      'order'            => new sfWidgetFormFilterInput(),
      'user_id'          => new sfWidgetFormFilterInput(),
      'store_types_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'StoreType')),
    ));

    $this->setValidators(array(
      'brand'            => new sfValidatorPass(array('required' => false)),
      'type'             => new sfValidatorPass(array('required' => false)),
      'model'            => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorPass(array('required' => false)),
      'area'             => new sfValidatorPass(array('required' => false)),
      'country'          => new sfValidatorPass(array('required' => false)),
      'city'             => new sfValidatorPass(array('required' => false)),
      'name'             => new sfValidatorPass(array('required' => false)),
      'address'          => new sfValidatorPass(array('required' => false)),
      'zip'              => new sfValidatorPass(array('required' => false)),
      'telf'             => new sfValidatorPass(array('required' => false)),
      'last_update'      => new sfValidatorPass(array('required' => false)),
      'product_line'     => new sfValidatorPass(array('required' => false)),
      'latitude'         => new sfValidatorPass(array('required' => false)),
      'longitude'        => new sfValidatorPass(array('required' => false)),
      'order'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'store_types_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'StoreType', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('stores_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addStoreTypesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.StoreStoreType StoreStoreType')
      ->andWhereIn('StoreStoreType.store_type_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Stores';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'brand'            => 'Text',
      'type'             => 'Text',
      'model'            => 'Text',
      'status'           => 'Text',
      'area'             => 'Text',
      'country'          => 'Text',
      'city'             => 'Text',
      'name'             => 'Text',
      'address'          => 'Text',
      'zip'              => 'Text',
      'telf'             => 'Text',
      'last_update'      => 'Text',
      'product_line'     => 'Text',
      'latitude'         => 'Text',
      'longitude'        => 'Text',
      'order'            => 'Number',
      'user_id'          => 'Number',
      'store_types_list' => 'ManyKey',
    );
  }
}
