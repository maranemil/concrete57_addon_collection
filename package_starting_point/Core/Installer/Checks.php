<?php /** @noinspection PhpUnused */
/** @noinspection PhpUndefinedClassInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace Concrete\Package\PackageStartingPoint\Core\Installer;

use Concrete\Core\Package\Package;

class Checks
{

    public static function someOtherPackageInstalled()
    {
        /*
         * Do some test that might return true, like checking to see
         * if another package is already installed.
         */
        $anotherPackage = Package::getByHandle('another_package');
        if ($anotherPackage) {
            	return true;
        }
        return false;
    }

}
