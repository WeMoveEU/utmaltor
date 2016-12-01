# UTMaltor

Alternation of UTM params in trackable urls.

## How it works?

This extension adds hook on Mailing edit operation and altering UTM params.
When user pastes a link to own website, the link will be extended with `utm_source`, `utm_medium` and `utm_campaign` params.

## Settings

Settings are available on page `/civicrm/utmaltor/settings`.

### domains

You have to set up list of domains separate by pipe, for example: `domain1.com|domain2.eu`. Only links to this domains will be altered.

### utm fields

It's possible to use variables in [Smarty](http://www.smarty.net) format.

* `{$mailing_id}`
* `{$campaign_id}`
* `{$data}` - current date, example with modifier: `{$date|date_format:"%Y-%m-%d"}`

## UTM in footer

This feature works based on not published hook `hook_civicrm_alterUrl`, yet.

Required configuration for CKEditor:

* Open file `sites/default/files/civicrm/persist/crm-ckeditor-config.js`
* Add new params:
    * `config.basicEntities = false;`
    * `config.entities = false;`
    * `config.forceSimpleAmpersand = true;`

This is a static file. Check your caching configuration on web server.

## Disclaimer

* Only HTML content is changed and only urls in **href** attribute.
