<?php

class CRM_Utmaltor_Logic_Smarty {

  public $variables = array();

  private $smarty = NULL;

  private $smartyCache;

  private static $instance = FALSE;

  private static $instanceParams = array();

  public static function singleton($params) {
    if (self::$instance == FALSE || self::hasNewParams($params)) {
      self::$instanceParams = $params;
      self::$instance = new CRM_Utmaltor_Logic_Smarty($params);
    }
    return self::$instance;
  }

  /**
   * Check whether params has new values.
   *
   * @param array $params
   *
   * @return bool
   */
  private static function hasNewParams($params) {
    return !!array_diff($params, self::$instanceParams);
  }

  private function __construct($params) {
    foreach ($params as $key => $param) {
      $this->variables[$key] = $param;
    }
    if (class_exists('CRM_Speakcivi_Logic_Campaign')) {
      $this->variables['campaign_lang'] = $this->getLanguage($params['campaign_id']);
    }
    $this->variables['date'] = date('YmdHis');
    $this->smartyCache = array();
    $this->smarty = CRM_Core_Smarty::singleton();
    $this->assign();
  }

  public function assign() {
    foreach ($this->variables as $variable => $value) {
      $this->smarty->assign($variable, $value);
    }
  }

  public function parse($urlTemplate) {
    if (!isset($this->smartyCache[$urlTemplate])) {
      $this->smartyCache[$urlTemplate] = $this->smarty->fetch('string:' . $urlTemplate);
    }
    return $this->smartyCache[$urlTemplate];
  }

  public function getLanguage($campaignId) {
    $campaign = new CRM_Speakcivi_Logic_Campaign($campaignId);
    $locale = $campaign->getLanguage();
    return strtoupper(substr($locale, 0, 2));
  }

}
