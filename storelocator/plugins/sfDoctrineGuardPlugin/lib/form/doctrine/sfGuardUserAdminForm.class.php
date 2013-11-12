<?php

/**
 * sfGuardUserAdminForm for admin generators
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUserAdminForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardUserAdminForm extends BasesfGuardUserAdminForm
{
  /**
   * @see sfForm
   */
  public function configure() {
    $this->widgetSchema['countries_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SlCountry', 'order_by' => array('name', 'asc') ));
    $this->validatorSchema['countries_list'] = new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SlCountry', 'required' => false));
  }//configure
  
  
  
  public function updateDefaultsFromObject() {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['countries_list'])) {
      $this->setDefault('countries_list', $this->object->Countries->getPrimaryKeys());
    }//if


  }

  protected function doSave($con = null) {
    $this->saveCountriesList($con);

    parent::doSave($con);
  }

  public function saveCountriesList($con = null) {
    if (!$this->isValid()) {
      throw $this->getErrorSchema();
    }//if

    if (!isset($this->widgetSchema['countries_list'])) {
      // somebody has unset this widget
      return;
    }//if

    if (null === $con) {
      $con = $this->getConnection();
    }//if

    $existing = $this->object->Countries->getPrimaryKeys();
    $values = $this->getValue('countries_list');
    if (!is_array($values)) {
      $values = array();
    }//if

    $unlink = array_diff($existing, $values);
    if (count($unlink)) {
      $this->object->unlink('Countries', array_values($unlink));
    }//if

    $link = array_diff($values, $existing);
    if (count($link)) {
      $this->object->link('Countries', array_values($link));
    }//if
  }//saveCountriesList
  
}
