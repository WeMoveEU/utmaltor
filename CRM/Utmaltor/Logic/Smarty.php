<?php

class CRM_Utmaltor_Logic_Smarty {

  public $variables = array();

  private $smarty = null;

  public function __construct($params) {
    $this->variables['mailing_id'] = $params['id'];
    $this->variables['campaign_id'] = $params['campaign_id'];
    $this->variables['campaign_lang'] = $this->getLanguage($params['campaign_id']);
    $this->variables['date'] = date('YmdHis');
    $this->smarty = CRM_Core_Smarty::singleton();
    $this->assign();
  }

  public function assign() {
    foreach ($this->variables as $variable => $value) {
      $this->smarty->assign($variable, $value);
    }
  }

  public function parse($urlTemplate) {
    return $this->smarty->fetch('string:' . $urlTemplate);
  }

  public function getLanguage($campaignId) {
    $campaign = new CRM_Speakcivi_Logic_Campaign($campaignId);
    $locale = $campaign->getLanguage();
    return strtoupper(substr($locale, 0, 2));
  }
}
