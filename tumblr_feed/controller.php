<?php /** @noinspection PhpUnused */
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection AutoloadingIssuesInspection */

/** @noinspection PhpUndefinedClassInspection */

namespace Concrete\Package\TumblrFeed;

use Package;
use BlockType;
use SinglePage;
use Loader;

#use Events;
#use User;
#use Group;
#use Concrete\Core\Html\Service\Html;
#use View;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

    protected $pkgHandle          = 'tumblr_feed';
    protected $appVersionRequired = '5.2.0';
    protected $pkgVersion         = '1.1.6';

    public function getPackageDescription()
    {
        return t("Allows the insertion of Tumblr Blogs and Entries into your site.");
    }

    public function getPackageName()
    {
        return t("Tumblr Feed");
    }

    public function install()
    {
        $pkg = parent::install();

        // install block
        BlockType::installBlockTypeFromPackage('tumblr_feed', $pkg);
        BlockType::installBlockTypeFromPackage('tumblr_entry', $pkg);

    }


}

