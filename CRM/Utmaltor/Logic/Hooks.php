<?php

class CRM_Utmaltor_Logic_Hooks {

  static $null = NULL;

  static function alterSmartyVariables($op, $objectName, $id, $params, &$smartyVariables) {
    return CRM_Utils_Hook::singleton()->invoke(5, $op, $objectName, $id, $params, $smartyVariables, self::$null, 'civicrm_utmaltorAlterSmartyVariables');
  }
}
