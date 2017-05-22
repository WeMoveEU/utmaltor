<?php

class CRM_Utmaltor_Logic_Smarty {

  public $variables = array();

  private $smarty = null;

  private $smartyCache;

  private static $instance = false;

  private static $instanceParams = array();

  public static function singleton($params) {
    if (self::$instance == false || self::hasNewParams($params)) {
      self::$instanceParams = $params;
      self::$instance = new CRM_Utmaltor_Logic_Smarty($params);
    }
    return self::$instance;
  }

  private static function hasNewParams($params) {
    /* assumption: $params never be empty */
    if (empty(self::$instanceParams)) {
      return true;
    }
    /* Assumption: this class uses smarty only with variables listed in $variables */
    if (self::$instanceParams['id'] == $params['id'] 
        && self::$instanceParams['campaign_id'] == $params['campaign_id']) {
      return false;
    }
    return true;
  }

  private function __construct($params) {
    $this->variables['mailing_id'] = $params['id'];
    $this->variables['campaign_id'] = $params['campaign_id'];
    $this->variables['campaign_lang'] = $this->getLanguage($params['campaign_id']);
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
