<?php

/**
 * SlStoreStoreType form base class.
 *
 * @method SlStoreStoreType getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSlStoreStoreTypeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sl_store_id'      => new sfWidgetFormInputHidden(),
      'sl_store_type_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'sl_store_id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('sl_store_id')), 'empty_value' => $this->getObject()->get('sl_store_id'), 'required' => false)),
      'sl_store_type_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('sl_store_type_id')), 'empty_value' => $this->getObject()->get('sl_store_type_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sl_store_store_type[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlStoreStoreType';
  }

}
