# UTMaltor

Alternation of UTM params in trackable urls.

## How works?

This extension adds hook on Mailing edit operation and altering UTM params (only if not specified).

* utm_campaign = civimail-XXX, where XXX = mailing_id
* utm_source = civimail
* utm_medium = email
