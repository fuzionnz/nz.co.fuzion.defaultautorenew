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
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function defaultautorenew_civicrm_xmlMenu(&$files) {
  _defaultautorenew_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function defaultautorenew_civicrm_postInstall() {
  _defaultautorenew_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function defaultautorenew_civicrm_uninstall() {
  _defaultautorenew_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function defaultautorenew_civicrm_enable() {
  _defaultautorenew_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function defaultautorenew_civicrm_disable() {
  _defaultautorenew_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function defaultautorenew_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _defaultautorenew_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function defaultautorenew_civicrm_managed(&$entities) {
  _defaultautorenew_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function defaultautorenew_civicrm_caseTypes(&$caseTypes) {
  _defaultautorenew_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function defaultautorenew_civicrm_angularModules(&$angularModules) {
  _defaultautorenew_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function defaultautorenew_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _defaultautorenew_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function defaultautorenew_civicrm_entityTypes(&$entityTypes) {
  _defaultautorenew_civix_civicrm_entityTypes($entityTypes);
}

function defaultautorenew_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_Contribution_Main' && !empty($form->_values['is_recur'])) {
    $defaults['is_recur'] = TRUE;
    $form->setDefaults($defaults);

    $ids = [];
    $auto = json_decode($form->get_template_vars('autoRenewMembershipTypeOptions'));
    foreach($auto as $key => $on) {
      if ($on) {
        list($ignore, $id) = explode('_', $key);
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
function defaultautorenew_civicrm_preProcess($formName, &$form) {

} // */

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
} // */
