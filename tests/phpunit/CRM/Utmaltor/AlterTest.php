<?php

require_once 'BaseTest.php';

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
class CRM_Utmaltor_AlterTest extends CRM_Utmaltor_BaseTest {

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
