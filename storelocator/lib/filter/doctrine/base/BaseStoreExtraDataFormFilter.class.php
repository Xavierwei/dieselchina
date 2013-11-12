<?php

/**
 * StoreExtraData filter form base class.
 *
 * @package    collections
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStoreExtraDataFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'store_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Store'), 'add_empty' => true)),
      'info'               => new sfWidgetFormFilterInput(),
      'additional_address' => new sfWidgetFormFilterInput(),
      'twotimeaday'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'opening_times'      => new sfWidgetFormFilterInput(),
      'times_notes'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'store_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Store'), 'column' => 'id')),
      'info'               => new sfValidatorPass(array('required' => false)),
      'additional_address' => new sfValidatorPass(array('required' => false)),
      'twotimeaday'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'opening_times'      => new sfValidatorPass(array('required' => false)),
      'times_notes'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('store_extra_data_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StoreExtraData';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'store_id'           => 'ForeignKey',
      'info'               => 'Text',
      'additional_address' => 'Text',
      'twotimeaday'        => 'Boolean',
      'opening_times'      => 'Text',
      'times_notes'        => 'Text',
    );
  }
}
