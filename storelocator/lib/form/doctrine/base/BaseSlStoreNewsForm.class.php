<?php

/**
 * SlStoreNews form base class.
 *
 * @method SlStoreNews getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSlStoreNewsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'store_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlStore'), 'add_empty' => true)),
      'title'      => new sfWidgetFormTextarea(),
      'paragraph'  => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'slug'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'store_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SlStore'), 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 1000)),
      'paragraph'  => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'slug'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'SlStoreNews', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('sl_store_news[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlStoreNews';
  }

}
