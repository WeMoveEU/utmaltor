# UTMaltor

Alternation of UTM params in trackable urls.

## How it works?

This extension adds hook on Mailing edit operation and altering UTM params.
When user pastes a link to own website, the link will be extended with `utm_source`, `utm_medium` and `utm_campaign` params.

## Settings

Settings are available on page `/civicrm/setting/utmaltor`.

### domains

You have to set up list of domains separate by pipe, for example: `domain1.com|domain2.eu`. Only links to this domains will be altered.

### utm fields

It's possible to use variables in [Smarty](http://www.smarty.net) format.

* `{$mailing_id}`
* `{$campaign_id}`
* `{$data}` - current date, example with modifier: `{$date|date_format:"%Y-%m-%d"}`

## UTM in footer

This feature works based on `hook_civicrm_alterMailContent`. There is needed a small patch to civicrm-core in order to have mailing_id and campaign_id visible by utmaltor.

```php
// todo add these lines in body of CRM_Mailing_BAO_Mailing::getTemplates() function
+ $this->templates['mailing_id'] = $this->id;
+ $this->templates['campaign_id'] = $this->campaign_id;

CRM_Utils_Hook::alterMailContent($this->templates);
```

Required configuration for CKEditor:

* Open file `sites/default/files/civicrm/persist/crm-ckeditor-config.js`
* Add new params:
    * `config.basicEntities = false;`
    * `config.entities = false;`
    * `config.forceSimpleAmpersand = true;`

This is a static file. Check your caching configuration on web server.

## Disclaimer

* Only HTML content is changed and only urls in **href** attribute.
