<?php

require_once 'defaultautorenew.civix.php';
use CRM_Defaultautorenew_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function defaultautorenew_civicrm_config(&$config) {
  _defaultautorenew_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function defaultautorenew_civicrm_install() {
  _defaultautorenew_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function defaultautorenew_civicrm_enable() {
  _defaultautorenew_civix_civicrm_enable();
}

function defaultautorenew_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_Contribution_Main') {
    if (!empty($form->_values['is_recur'])) {
      $defaults['is_recur'] = TRUE;
      $form->setDefaults($defaults);
    }

    $ids = [];
    $auto = json_decode($form->get_template_vars('autoRenewMembershipTypeOptions'));
    foreach((array) $auto as $key => $on) {
      if ($on) {
        list(, $id) = explode('_', $key);
        $ids[] = $id;
      }
    }

    if (!empty($ids)) {
      $manager = CRM_Core_Resources::singleton();
      $manager->addSetting(array(
        'autoRenewIds' => $ids,
      ));
      $manager->addScriptFile('nz.co.fuzion.defaultautorenew', 'defaultautorenew.js');
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
 */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function defaultautorenew_civicrm_navigationMenu(&$menu) {
  _defaultautorenew_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _defaultautorenew_civix_navigationMenu($menu);
}
 */
