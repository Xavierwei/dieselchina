<?php

/**
 * SlStore filter form base class.
 *
 * @package    collections
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSlStoreFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                  => new sfWidgetFormFilterInput(),
      'address'               => new sfWidgetFormFilterInput(),
      'zip'                   => new sfWidgetFormFilterInput(),
      'telf'                  => new sfWidgetFormFilterInput(),
      'email'                 => new sfWidgetFormFilterInput(),
      'latitude'              => new sfWidgetFormFilterInput(),
      'longitude'             => new sfWidgetFormFilterInput(),
      'position'              => new sfWidgetFormFilterInput(),
      'world_area_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlWorldArea'), 'add_empty' => true)),
      'country_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlCountry'), 'add_empty' => true)),
      'city_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlCity'), 'add_empty' => true)),
      'store_type_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlStoreType'), 'add_empty' => true)),
      'store_status_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlStoreStatus'), 'add_empty' => true)),
      'product_line_id'       => new sfWidgetFormFilterInput(),
      'online'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'                  => new sfWidgetFormFilterInput(),
      'sl_product_lines_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SlProductLine')),
      'sl_store_types_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SlStoreType')),
    ));

    $this->setValidators(array(
      'name'                  => new sfValidatorPass(array('required' => false)),
      'address'               => new sfValidatorPass(array('required' => false)),
      'zip'                   => new sfValidatorPass(array('required' => false)),
      'telf'                  => new sfValidatorPass(array('required' => false)),
      'email'                 => new sfValidatorPass(array('required' => false)),
      'latitude'              => new sfValidatorPass(array('required' => false)),
      'longitude'             => new sfValidatorPass(array('required' => false)),
      'position'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'world_area_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SlWorldArea'), 'column' => 'id')),
      'country_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SlCountry'), 'column' => 'id')),
      'city_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SlCity'), 'column' => 'id')),
      'store_type_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SlStoreType'), 'column' => 'id')),
      'store_status_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SlStoreStatus'), 'column' => 'id')),
      'product_line_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'online'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'                  => new sfValidatorPass(array('required' => false)),
      'sl_product_lines_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SlProductLine', 'required' => false)),
      'sl_store_types_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SlStoreType', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sl_store_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addSlProductLinesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.SlStoreProductLine SlStoreProductLine')
      ->andWhereIn('SlStoreProductLine.sl_product_line_id', $values)
    ;
  }

  public function addSlStoreTypesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.SlStoreStoreType SlStoreStoreType')
      ->andWhereIn('SlStoreStoreType.sl_store_type_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'SlStore';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'name'                  => 'Text',
      'address'               => 'Text',
      'zip'                   => 'Text',
      'telf'                  => 'Text',
      'email'                 => 'Text',
      'latitude'              => 'Text',
      'longitude'             => 'Text',
      'position'              => 'Number',
      'world_area_id'         => 'ForeignKey',
      'country_id'            => 'ForeignKey',
      'city_id'               => 'ForeignKey',
      'store_type_id'         => 'ForeignKey',
      'store_status_id'       => 'ForeignKey',
      'product_line_id'       => 'Number',
      'online'                => 'Boolean',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
      'slug'                  => 'Text',
      'sl_product_lines_list' => 'ManyKey',
      'sl_store_types_list'   => 'ManyKey',
    );
  }
}
