<?php /** @noinspection SpellCheckingInspection */
/** @noinspection PhpUnused */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection PhpUndefinedClassInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace Concrete\Package\HtmlList\Block\HtmlList;

use Concrete\Core\Block\BlockController;
use UserInfo;
use Loader;
use Config;
use Page;
use View;

defined('C5_EXECUTE') or die("Access Denied.");

/**
 * @property $title
 * @property $list
 * @method set(string $string, false|string[] $getItemArray)
 */
class Controller extends BlockController
{

    protected $btTable           = 'btHtmlList';
    protected $btWrapperClass    = 'ccm-ui';
    protected $btInterfaceWidth  = "580";
    protected $btInterfaceHeight = "360";

    private $delimiter = "\n"; // used in storing values to the database. This is private because it must coordinate with the delimiter in auto.js

    public function getBlockTypeDescription()
    {
        return t("Display a list of items with a title.");
    }

    public function getBlockTypeName()
    {
        return t("HTML List");
    }

    public function getSearchableContent()
    {

        // return the list title followed by the raw list value and seperated by the delimiter value.
        return $this->title . $this->delimiter . $this->list;

    }

    public function getItemArray()
    {

        // explode the items value by the delimiter value (default setting the new line (\n) char).
        return explode($this->delimiter, $this->list);

    }

    public function view()
    {

        // set $items to the items array retreived by getItemArray()
        $this->set('items', $this->getItemArray());

    }

    public function edit()
    {

        // set $items to the items array retreived by getItemArray()
        $this->set('items', $this->getItemArray());

    }

    public function add()
    {

        // $items defaults to an empty array.
        $this->set('items', array());

    }

    public function save($data)
    {

        // set title and list values from form submission.
        $args['list'] = $data['list'];
        $args['title'] = $data['title'];

        parent::save($args);

    }

}

