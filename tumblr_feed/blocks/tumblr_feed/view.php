<?php /** @noinspection PhpUndefinedVariableInspection */
defined('C5_EXECUTE') or die("Access Denied.");

echo '<h2 class="tumblrfeed-main-title">' . $blockTitle . '</h2>';
echo '<div id="tumblrfeed-' . $tumblrUsername . '-loading"><h2 style="text-align: center;">Loading</h2>';

echo '<p style="text-align: center;">
		<img src="' . DIR_REL . '/packages/tumblr_feed/blocks/tumblr_feed/images/ajax-loader.gif"
		alt="Loading" title="Loading" />
		</p></div>';

echo '<div id="tumblrfeed-' . $tumblrUsername . '">';
echo '</div>';

echo '<script type="text/javascript"
            src="' . DIR_REL . '/packages/tumblr_feed/blocks/tumblr_feed/view.js">
        </script>';

echo '<script type="text/javascript">
            let actionurl =  "' . $this->action('getBlogHTML') . '";
		    //getBlog("' . str_replace("&amp;", "&", $this->action("getBlogHTML")) . '",1,"' . $tumblrUsername . '");
		    getBlog(actionurl,1,"' . $tumblrUsername . '");
		</script>';

