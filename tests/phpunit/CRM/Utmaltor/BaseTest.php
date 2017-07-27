<?php

use \Civi\Test\HookInterface;
use \Civi\Test\HeadlessInterface;
use \Civi\Test\TransactionalInterface;


/**
 * This is a lightweight unit-tested based on PHPUnit_Framework_TestCase.
 *
 * PHPUnit_Framework_TestCase is suitable for any of these:
 *  - Running tests which don't require any database.
 *  - Running tests on the main/live database.
 *  - Customizing the setup/teardown processes.
 *
 * @group headless
 */
class CRM_Utmaltor_BaseTest extends \PHPUnit_Framework_TestCase
  implements HeadlessInterface, HookInterface, TransactionalInterface {

  public $utmSmarty;

  public $source = '';
  public $medium = '';
  public $campaign = '';

  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  public function setUp() {
    $this->settings();
    $mailingId = 1000;
    $params = [
      'id' => $mailingId,
      'campaign_id' => 200,
    ];
    $this->utmSmarty = \CRM_Utmaltor_Logic_Smarty::singleton($params);
  }

  private function settings() {
    $this->source = 'civimail-{$mailing_id}';
    $this->medium = 'email';
    $this->campaign = '{$date|date_format:"%Y%m%d"}{if $campaign_lang}_{$campaign_lang}{/if}';
    Civi::settings()->set('utmaltor_domains', 'wemove.eu');
    Civi::settings()->set('utmaltor_source', $this->source);
    Civi::settings()->set('utmaltor_medium', $this->medium);
    Civi::settings()->set('utmaltor_campaign', $this->campaign);
    Civi::settings()->set('utmaltor_source_override', 1);
    Civi::settings()->set('utmaltor_medium_override', 1);
    Civi::settings()->set('utmaltor_campaign_override', 0);
  }

}
