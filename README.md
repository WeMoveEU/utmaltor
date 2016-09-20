# UTMaltor

Alternation of UTM params in trackable urls.

## How works?

This extension adds hook on Mailing edit operation and altering UTM params.

### only if not specified

* utm_campaign = YYYYMMDD_LN, where:
    * YYYYMMDD is current date
    * _LN is language from campaign (if mailing is linked with campaign and when this campaign has language)
      * depends on [speakcivi](https://github.com/WeMoveEU/speakcivi) extension

### override existing

* utm_source = civimail-XXX, where XXX = mailing_id
* utm_medium = email

## Disclaimer

* Only HTML content is changed and only urls in **href** attribute.
* This extension doesn't touch links from footer.
