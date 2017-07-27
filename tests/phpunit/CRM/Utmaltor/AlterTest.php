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
class CRM_Utmaltor_AlterTest extends \PHPUnit_Framework_TestCase
  implements HeadlessInterface, HookInterface, TransactionalInterface {

  private $utmSmarty;

  private $source = '';
  private $medium = '';
  private $campaign = '';

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
      'campaign_lang' => '',
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

  public function testAlterCleanUrl() {
    $url = 'https://www.wemove.eu/test';
    $expectedUrl = implode('', [
      'https://www.wemove.eu/test?utm_source=',
      $this->utmSmarty->parse($this->source),
      '&utm_medium=',
      $this->utmSmarty->parse($this->medium),
      '&utm_campaign=',
      $this->utmSmarty->parse($this->campaign),
    ]);
    $actualUrl = \CRM_Utmaltor_Logic_Alter::url($url, $this->utmSmarty);
    $this->assertEquals($expectedUrl, $actualUrl);
  }

  public function testAlterUrlWithOverratedMedium() {
    $url = 'https://www.wemove.eu/test?utm_medium=MEDIUM';
    $expectedUrl = implode('', [
      'https://www.wemove.eu/test?utm_medium=',
      $this->utmSmarty->parse($this->medium),
      '&utm_source=',
      $this->utmSmarty->parse($this->source),
      '&utm_campaign=',
      $this->utmSmarty->parse($this->campaign),
    ]);
    $actualUrl = \CRM_Utmaltor_Logic_Alter::url($url, $this->utmSmarty);
    $this->assertEquals($expectedUrl, $actualUrl);
  }

  public function testAlterUrlWithOverratedSource() {
    $url = 'https://www.wemove.eu/test?utm_source=SOURCE';
    $expectedUrl = implode('', [
      'https://www.wemove.eu/test?utm_source=',
      $this->utmSmarty->parse($this->source),
      '&utm_medium=',
      $this->utmSmarty->parse($this->medium),
      '&utm_campaign=',
      $this->utmSmarty->parse($this->campaign),
    ]);
    $actualUrl = \CRM_Utmaltor_Logic_Alter::url($url, $this->utmSmarty);
    $this->assertEquals($expectedUrl, $actualUrl);
  }

  public function testAlterUrlWithCustomCampaign() {
    $url = 'https://www.wemove.eu/test?utm_campaign=CAMPAIGN';
    $expectedUrl = implode('', [
      'https://www.wemove.eu/test?utm_campaign=CAMPAIGN',
      '&utm_source=',
      $this->utmSmarty->parse($this->source),
      '&utm_medium=',
      $this->utmSmarty->parse($this->medium),
    ]);
    $actualUrl = \CRM_Utmaltor_Logic_Alter::url($url, $this->utmSmarty);
    $this->assertEquals($expectedUrl, $actualUrl);
  }

}
