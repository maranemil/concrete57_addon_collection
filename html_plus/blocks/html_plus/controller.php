<?php /** @noinspection PhpUndefinedVariableInspection */
/** @noinspection NotOptimalRegularExpressionsInspection */
/** @noinspection PhpUnused */
/** @noinspection PhpParamsInspection */
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection PhpUndefinedConstantInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUndefinedClassInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace Concrete\Package\HtmlPlus\Block\HtmlPlus;

use Concrete\Core\Block\BlockController;
use UserInfo;
use Loader;
use Config;
use Page;
use View;

defined('C5_EXECUTE') or die("Access Denied.");

/**
 * @method set(string $string, Controller $processContent)
 */
class Controller extends BlockController
{

    protected $btTable           = 'btHtmlPlus';
    protected $btInterfaceWidth  = "960";
    protected $btInterfaceHeight = "550";

    protected $btCacheBlockRecord                   = true;
    protected $btCacheBlockOutput                   = true;
    protected $btCacheBlockOutputOnPost             = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime           = CACHE_LIFETIME;

    public $htmlPlusContent = "";

    public function getBlockTypeName()
    {
        return t("HTML Plus");
    }

    public function getBlockTypeDescription()
    {
        return t("A basic HTML editor with code highlighting");
    }

    public function getSearchableContent()
    {
        return $this->htmlPlusContent;
    }

    public function view()
    {
        $this->set('htmlPlusContent', $this->processContent());
    }

    public function edit()
    {
        $this->set('htmlElements', $this->getHtmlElements());
        $this->set('htmlPlusContent', $this->processContent());
    }

    public function add()
    {
        $this->set('htmlElements', $this->getHtmlElements());
    }

    public function getHtmlElements()
    {
        //Define HTML element quick-tags (grouped to create headings)
        $htmlElements[t('Text')] = array(
            'P'      => '<p></p>',
            'Strong' => '<strong></strong>',
            'Small'  => '<small></small>',
            'EM'     => '<em></em>',
            'I'      => '<i></i>',
            'H1'     => '<h1></h1>',
            'H2'     => '<h2></h2>',
            'H3'     => '<h3></h3>',
            'H4'     => '<h4></h4>',
            'Quote'  => '<blockquote></blockquote>'
        );
        $htmlElements[t('Layout')] = array(
            'Div'  => '<div></div>',
            'Span' => '<span></span>',
            'BR'   => '<br />'
        );
        $htmlElements[t('Lists')] = array(
            'UL' => '<ul></ul>',
            'OL' => '<ol></ol>',
            'LI' => '<li></li>'
        );
        $htmlElements[t('Links &amp; Images')] = array(
            'Link' => '<a href="" title=""></a>',
            'Img'  => '<img src="" alt="" width="" height="" />'
        );
        $htmlElements[t('Tables')] = array(
            'Table' => '<table></table>',
            'TR'    => '<tr></tr>',
            'TD'    => '<td></td>'
        );
        $htmlElements[t('Forms')] = [
            'Form'       => '<form name="" action="" ></form>',
            'Fieldset'   => '<fieldset></fieldset>',
            'Label'      => '<label></label>',
            'Input Text' => '<input type="text" name="" value="" />',
            'Textarea'   => '<textarea name=""></textarea>'
        ];
        $htmlElements[t('Hooks')] = array(
            'Class' => 'class=""',
            'ID'    => 'id=""'
        );
        return $htmlElements;
    }

    public function processContent()
    {
        return $this->replaceLinks($this->htmlPlusContent);
    }

    public function replaceLinks($htmlPlusContent)
    {

        $nh = Loader::helper('navigation');
        preg_match_all('/index\.php\?cID=([0-9]+)/', $htmlPlusContent, $links);

        foreach ($links[1] as $pageID) {
            $page = Page::getByID($pageID);
            $realLinks[] = $nh->getLinkToCollection($page);
        }

        $htmlPlusContent = str_replace($links[0], $realLinks, $htmlPlusContent);
        $this->htmlPlusContent = $htmlPlusContent;
        return $this;
    }

    public function save($data)
    {
        $args['htmlPlusContent'] = isset($data['htmlPlusContent']) ? $data['htmlPlusContent'] : '';
        parent::save($args);
    }

}