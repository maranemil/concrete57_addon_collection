<?php /** @noinspection PhpUndefinedConstantInspection */
defined('C5_EXECUTE') or die("Access Denied.");

if (isset($_REQUEST['tumblrEntryID'], $_REQUEST['tumblrUsername'])) {
    $entryUsername = $_REQUEST['tumblrUsername'];
    $entryToDisplay = $_REQUEST['tumblrEntryID'];
    echo '<div id="tumblrentry-loading"><h2 style="text-align: center;">Loading</h2>';
    echo '<p style="text-align: center;"><img src="' . DIR_REL . '/packages/tumblr_feed/blocks/tumblr_entry/images/ajax-loader.gif" alt="Loading" title="Loading" /></p></div>';
    echo '<div id="tumblrentry">';
    echo '</div>';
    echo "<script type=\"text/javascript\">getEntry('" . str_replace("&amp;", "&", $this->action('getEntryHTML')) . "','" . $entryToDisplay . "','" . $entryUsername . "');</script>";
} else {
    echo '<p class="tumblrentry-error">No Tumblr entry was specified in the HTML header.</p>';
}

