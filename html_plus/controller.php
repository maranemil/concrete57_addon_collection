<?php /** @noinspection SpellCheckingInspection */
/** @noinspection PhpUnused */
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection AutoloadingIssuesInspection */

/** @noinspection PhpUndefinedClassInspection */

namespace Concrete\Package\HtmlPlus;

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

    protected $pkgHandle          = 'html_plus';
    protected $appVersionRequired = '5.6.0';
    protected $pkgVersion         = '1.0.1';

    public function getPackageDescription()
    {
        return t("An HTML editor that uses the CodeMirror project library to provide a richer editing experience.");
    }

    public function getPackageName()
    {
        return t("HTML Plus");
    }

    public function install()
    {
        $pkg = parent::install();

        // install block
        BlockType::installBlockTypeFromPackage('html_plus', $pkg);
    }

}

