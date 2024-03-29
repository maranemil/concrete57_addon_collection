<?php /** @noinspection PhpUnused */
/** @noinspection PhpUndefinedClassInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace Concrete\Package\PackageStartingPoint\Service;

use Concrete\Core\Foundation\Object;
use Concrete\Core\Events\EventsServiceProvider as EventsProvider;

class Events extends Object
{
    public static function addOnStart()
    {
        /*
         * An example of adding a listener that is triggered when a
         * page is updated.
         */
        EventsProvider::addListener('on_page_update', static function ($page) {
            // do something when the page is updated
        });
    }
}
