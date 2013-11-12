<?php

/**
 * SlStoreType filter form base class.
 *
 * @package    collections
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSlStoreTypeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ord'           => new sfWidgetFormFilterInput(),
      'sl_store_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SlStore')),
    ));

    $this->setValidators(array(
      'name'          => new sfValidatorPass(array('required' => false)),
      'ord'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sl_store_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SlStore', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sl_store_type_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addSlStoreListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('SlStoreStoreType.sl_store_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'SlStoreType';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'name'          => 'Text',
      'ord'           => 'Number',
      'sl_store_list' => 'ManyKey',
    );
  }
}
