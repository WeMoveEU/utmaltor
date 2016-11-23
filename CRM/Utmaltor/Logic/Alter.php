<?php

class CRM_Utmaltor_Logic_Alter {

  public static function url($url) {
    $url = self::alterSource($url);
    $url = self::alterMedium($url);
    $url = self::alterCampaign($url);
    return $url;
  }

  function alterSource($url) {
    $key = 'utm_source';
    $value = CRM_Core_BAO_Setting::getItem('UTMaltor Preferences', 'utmaltor_source');
    return self::setKey($url, $key, $value, TRUE);
  }

  function alterMedium($url) {
    $key = 'utm_medium';
    $value = CRM_Core_BAO_Setting::getItem('UTMaltor Preferences', 'utmaltor_medium');
    return self::setKey($url, $key, $value, TRUE);
  }

  function alterCampaign($url) {
    $key = 'utm_campaign';
    $value = CRM_Core_BAO_Setting::getItem('UTMaltor Preferences', 'utmaltor_campaign');
    return self::setKey($url, $key, $value);
  }

  function setKey($url, $key, $value, $override = FALSE) {
    if ($override) {
      return setValue($url, $key, $value);
    }
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
    if (!array_key_exists('path', $urlParts)) {
      $urlParts['path'] = '/';
    }
    $urlParts['query'] = http_build_query($query ? array_merge($query, array($key => $value)) : array($key => $value));
    $newUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . $urlParts['query'];
    $tokens = array(
      '%7B' => '{',
      '%7D' => '}',
      '{contact_checksum}=' => '{contact.checksum}', // #3 Token {contact_checksum} breaks down links
      '{contact.checksum}=' => '{contact.checksum}', // #3 Token {contact_checksum} breaks down links
    );
    $newUrl = str_replace(array_keys($tokens), array_values($tokens), $newUrl);
    return $newUrl;
  }
}
