<?php

/**
 * StoreExtraData form base class.
 *
 * @method StoreExtraData getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStoreExtraDataForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'store_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Store'), 'add_empty' => true)),
      'info'               => new sfWidgetFormTextarea(),
      'additional_address' => new sfWidgetFormInputText(),
      'twotimeaday'        => new sfWidgetFormInputCheckbox(),
      'opening_times'      => new sfWidgetFormInputText(),
      'times_notes'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'store_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Store'), 'required' => false)),
      'info'               => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'additional_address' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'twotimeaday'        => new sfValidatorBoolean(array('required' => false)),
      'opening_times'      => new sfValidatorPass(array('required' => false)),
      'times_notes'        => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('store_extra_data[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StoreExtraData';
  }

}
