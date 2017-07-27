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
    $mailingId = 1000;
    $params = [
      'id' => $mailingId,
      'campaign_id' => 200,
      'campaign_lang' => '',
    ];
    $this->utmSmarty = \CRM_Utmaltor_Logic_Smarty::singleton($params);
    $this->source = Civi::settings()->get('utmaltor_source');
    $this->medium = Civi::settings()->get('utmaltor_medium');
    $this->campaign = Civi::settings()->get('utmaltor_campaign');
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

}
