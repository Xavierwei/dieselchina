<?php

/**
 * SlExtraData filter form base class.
 *
 * @package    collections
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSlExtraDataFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'datakey'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'value'    => new sfWidgetFormFilterInput(),
      'store_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlStore'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'datakey'  => new sfValidatorPass(array('required' => false)),
      'value'    => new sfValidatorPass(array('required' => false)),
      'store_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SlStore'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('sl_extra_data_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlExtraData';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'datakey'  => 'Text',
      'value'    => 'Text',
      'store_id' => 'ForeignKey',
    );
  }
}
