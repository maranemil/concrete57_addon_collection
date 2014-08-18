<?php

namespace Concrete\Package\HtmlPlus\Block\HtmlPlus;
use UserInfo;
use Concrete\Block\PageList;
use Loader;
use Block;
use Config;
use Page;
use View;
#use \Concrete\Core\Form\Service\Widget\PageSelector;
use \Concrete\Package\HtmlPlus\Block\HtmlPlus\Controller as HtmlPlusController;

defined('C5_EXECUTE') or die("Access Denied.");

$blocksInArea = Page::getCurrentPage()->getBlocks('Main');
$block = Block::getByID($blocksInArea[0]->bID);
$bID = $block->bID;
?>

<div id="htmlPlusContentBlock<?php echo intval($bID)?>" class="htmlPlusContentBlock">
<?php
   # $hpc = new HtmlPlusController();
    #$htmlPlusContent = $hpc->processContent();
	#print $htmlPlusContent;

    $objArray =  (array) $htmlPlusContent;
    echo $objArray["htmlPlusContent"];
    #print h($htmlPlusContent, ENT_COMPAT, APP_CHARSET);
?>
</div>