# UTMaltor

Alternation of UTM mailing params.

## How works?

This extension depends on additional hooks pre/post added to TrackableURL object.

This extension adds hook on TrackableURL create operation and altering UTM params (only if not specified).

* utm_campaign = civimail-XXX, where XXX = mailing_id
* utm_source = civimail
* utm_medium = email
