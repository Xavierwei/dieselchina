<?php

/**
 * SlStoreType form base class.
 *
 * @method SlStoreType getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSlStoreTypeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'name'          => new sfWidgetFormInputText(),
      'ord'           => new sfWidgetFormInputText(),
      'sl_store_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SlStore')),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'          => new sfValidatorString(array('max_length' => 255)),
      'ord'           => new sfValidatorInteger(array('required' => false)),
      'sl_store_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SlStore', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sl_store_type[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlStoreType';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['sl_store_list']))
    {
      $this->setDefault('sl_store_list', $this->object->SlStore->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSlStoreList($con);

    parent::doSave($con);
  }

  public function saveSlStoreList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sl_store_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->SlStore->getPrimaryKeys();
    $values = $this->getValue('sl_store_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('SlStore', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('SlStore', array_values($link));
    }
  }

}
