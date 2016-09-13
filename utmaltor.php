<?php

require_once 'utmaltor.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function utmaltor_civicrm_config(&$config) {
  _utmaltor_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function utmaltor_civicrm_xmlMenu(&$files) {
  _utmaltor_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function utmaltor_civicrm_install() {
  _utmaltor_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function utmaltor_civicrm_uninstall() {
  _utmaltor_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function utmaltor_civicrm_enable() {
  _utmaltor_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function utmaltor_civicrm_disable() {
  _utmaltor_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function utmaltor_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _utmaltor_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function utmaltor_civicrm_managed(&$entities) {
  _utmaltor_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function utmaltor_civicrm_caseTypes(&$caseTypes) {
  _utmaltor_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function utmaltor_civicrm_angularModules(&$angularModules) {
_utmaltor_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function utmaltor_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _utmaltor_civix_civicrm_alterSettingsFolders($metaDataFolders);
}


function utmaltor_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'TrackableURL' and $op == 'create') {
//    urls have to be changed directly in body of email
//    in other hand on each call civicrm will be created new trackable url
//    $params['url'] = alterCampaign($params['url'], $params['mailing_id']);
//    $params['url'] = alterSource($params['url']);
//    $params['url'] = alterMedium($params['url']);
  }
  // fixme remove restriction to specific mailing
  if ($objectName == 'Mailing' and $op == 'edit' && $id == 3085) {
    CRM_Core_Error::debug_var('Mailing edit $params', $params['body_html']);
  }
}



function alterCampaign($url, $mailingId) {
  $key = 'utm_campaign';
  $value = 'civimail-'.$mailingId;
  return setKey($url, $key, $value);
}

function alterSource($url) {
  $key = 'utm_source';
  $value = 'civimail';
  return setKey($url, $key, $value);
}

function alterMedium($url) {
  $key = 'utm_medium';
  $value = 'email';
  return setKey($url, $key, $value);
}

function setKey($url, $key, $value) {
  if ((strpos($url, $key) === FALSE) || (strpos($url, $key) !== FALSE && !getValue($url, $key))) {
    return setValue($url, $key, $value);
  }
  return $url;
}

function getValue($url, $key) {
  $query = parse_url($url, PHP_URL_QUERY);
  parse_str($query, $arr);
  if (array_key_exists($key, $arr)) {
    return trim($arr[$key]);
  }
  return "";
}

function setValue($url, $key, $value) {
  $urlParts = parse_url($url);
  if (array_key_exists('query', $urlParts)) {
    parse_str($urlParts['query'], $query);
  } else {
    $query = array();
  }
  $urlParts['query'] = http_build_query($query ? array_merge($query, array($key => $value)) : array($key => $value));
  return $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . $urlParts['query'];
}
