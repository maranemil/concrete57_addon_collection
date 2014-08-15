<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

$body = t("You (or someone pretending to be you) recently signed up for the %s Mailing List.
To confirm your subscription, click the link below:
%s

If you have received this in error or do not wish to subscribe to the list, simply ignore this email.

Thanks!

", SITE, $confirmation_url);
