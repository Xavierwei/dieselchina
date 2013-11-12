<?php

/**
 * SlExtraData form base class.
 *
 * @method SlExtraData getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSlExtraDataForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'datakey'  => new sfWidgetFormInputText(),
      'value'    => new sfWidgetFormTextarea(),
      'store_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlStore'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'datakey'  => new sfValidatorString(array('max_length' => 255)),
      'value'    => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'store_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SlStore'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sl_extra_data[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlExtraData';
  }

}
