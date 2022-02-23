<?php /** @noinspection SpellCheckingInspection */
/** @noinspection PhpUnused */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUndefinedFunctionInspection */

/** @noinspection PhpUndefinedClassInspection */

namespace Concrete\Package\HtmlList;

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

    protected $pkgHandle          = 'html_list';
    protected $appVersionRequired = '5.5.0';
    protected $pkgVersion         = '1.0';

    public function getPackageDescription()
    {
        return t("Add ordered and unordered lists to your site.");
    }

    public function getPackageName()
    {
        return t("HTML List");
    }

    public function install()
    {

        $pkg = parent::install();

        // install list block
        BlockType::installBlockTypeFromPackage('html_list', $pkg);

    }

}