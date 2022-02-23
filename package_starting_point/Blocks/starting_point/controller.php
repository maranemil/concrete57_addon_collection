<?php /** @noinspection PhpUnused */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUndefinedNamespaceInspection */

// Image associated with this package was provided by dakirby309 at http://dakirby309.deviantart.com/.

namespace Concrete\Package\PackageStartingPoint\Block\StartingPoint;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Database\Database;

/**
 * @method render(string $string)
 * @property $db
 */
class Controller extends BlockController
{

    protected $btTable           = 'btStartingPoint';
    protected $btInterfaceWidth  = "400";
    protected $btInterfaceHeight = "400";

    public function __construct($obj = null)
    {
        parent::__construct($obj);
        $this->db = Database::getActiveConnection();
    }

    public function getBlockTypeName()
    {
        return t("Starting Point");
    }

    public function getBlockTypeDescription()
    {
        return t("Add a really simple block to your site");
    }

    public function add()
    {
        parent::add();
    }

    public function edit()
    {
        parent::edit();
    }

    public function view()
    {

    }

    public function save($args = array())
    {
        parent::save($args);
    }

    public function duplicate($newBlockId)
    {
        parent::duplicate($newBlockId);

    }

    public function getSearchableContent()
    {
        ob_start();
        $this->render('view');
        return ob_get_clean();
    }

}