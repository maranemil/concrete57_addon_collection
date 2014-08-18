<?php

namespace Concrete\Package\TumblrFeed\Block\TumblrFeed;

use \Concrete\Core\Block\BlockController;
use \Concrete\Core\View\AbstractView;
use UserInfo;
use Loader;
use Config;
use Page;
#use View;
use Block;
use BlockType;
#use URL;
#use Area;
use \Concrete\Core\Area\Area;
use Environment;
use CacheLocal;
use \Concrete\Core\Block\View\BlockView;
use \Concrete\Core\Block\View;
use \Concrete\Core\Routing\URL;


class BlockViewExpand extends BlockView {

    public $blockOpen;
    public $bID;

    public function getPassThruActionOpen()
    {
        $c = Page::getCurrentPage();

        $blocksInArea = Page::getCurrentPage()->getBlocks('Main');
        $block = Block::getByID($blocksInArea[0]->bID);
        $bID = $block->bID;

        if (is_object($c)) {
            $cnt = $c->getController();
            $parameters = $cnt->getParameters();
            $action = $cnt->getAction();
            if ($action == 'passthru' && $bID == $parameters[1]) {
                return 'action_' . $parameters[2];
            }
        }
    }

    public function actionOpen($task)
    {
        $c = Page::getCurrentPage();
        $blocksInArea = $c->getBlocks('Main');
        foreach($blocksInArea as $dBlock){
            if($dBlock->btHandle=="tumblr_feed"){ //  || $dBlock->btTable=="btTumblrFeed"
                $b = Block::getByID($dBlock->bID);
            }
        }

        $a = Area::get($c,"Main");
        try {
            if (is_object($b)) {
                if (is_object($c)) {
                    return URL::page($c, 'passthru', urlencode($a->getAreaHandle()), $b->getBlockID(), $task);
                }
            }
        } catch (Exception $e) {
            #throw new Exception('Division durch Null.');
            echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
        }
    }

}

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController {

/**
 * An object used by the TumbrFeed Block to display entries of a Tumblr account
 *
 * @package Blocks
 * @subpackage BlockTypes
 * @author Stuart McHattie <stuart.mchattie@gmail.com>
 * @category Concrete
 * @copyright  Copyright (c) 2012 Stuart McHattie. (http://www.sdjmchattie.com)
 *
 */

	#class TumblrFeedBlockController extends BlockController {
		
		var $pobj;
		
		protected $btDescription = "Allows the insertion of Tumblr Blogs into your site.";
		protected $btName = "Tumblr Feed";
		protected $btTable = 'btTumblrFeed';
		protected $btInterfaceWidth = "350";
		protected $btInterfaceHeight = "350";
		
		function insertPagination($postsPerPage, $nPostsInFeed, $currentPage) {
			$nPagesInFeed = ceil($nPostsInFeed / $postsPerPage);
			$lowPage = max($currentPage - 2, 1);
			$highPage = min($currentPage + 2, $nPagesInFeed);


            $blocksInArea = Page::getCurrentPage()->getBlocks('Main');
            $b = Block::getByID($blocksInArea[0]->bID);
            #$url = $b->passThruBlock($_REQUEST['method']);
            $bID = $b->bID;


            #$page = Page::getCurrentPage();
            #$nh = Loader::helper('navigation');
            #$url = $nh->getCollectionURL($page)."/passthru/Main/78/getBlogHTML";

            $url =  BlockViewExpand::actionOpen("getBlogHTML"); //

           # global $b;
			#$url = $b->getBlockPassThruAction(). '&method=getBlogHTML';
            #$url = BlockViewExpand::getPassThruActionOpen(). '&method=getBlogHTML';
            #$url = $this->controller->action("getBlogHTML"). '?method=getBlogHTML';

			echo '&nbsp;&nbsp;&nbsp;<a href="javascript:getBlog(\'' . $url . '\',1,\'' . $this->tumblrUsername . '\')">Latest</a>&nbsp;&nbsp;';
			if ($currentPage > 1) { echo '<a href="javascript:getBlog(\'' . $url . '\',' . ($currentPage - 1) . ',\'' . $this->tumblrUsername . '\')">Prev</a>&nbsp;&nbsp;'; }
			for ($i=$lowPage; $i<=$highPage; ++$i) {
				if ($i != $lowpage) { echo ' '; }
				if ($i != $currentPage) { echo '<a href="javascript:getBlog(\'' . $url . '\',' . $i . ',\'' . $this->tumblrUsername . '\')">[' . $i . ']</a>'; }
				else { echo'[' . $i . ']'; }
			}
			if ($currentPage < $nPagesInFeed) { echo '&nbsp;&nbsp;<a href="javascript:getBlog(\'' . $url . '\',' . ($currentPage + 1) . ',\'' . $this->tumblrUsername . '\')">Next</a>'; }
			echo '&nbsp;&nbsp;<a href="javascript:getBlog(\'' . $url . '\',' . $nPagesInFeed . ',\'' . $this->tumblrUsername . '\')">Oldest</a>';
		}
		
		function parseHtmlNumChars($htmlToParse, $charLimit) {
			$chunks = preg_split('/<.*?>/', $htmlToParse);
			$numChars = 0;
			$lastChunk = '';
			foreach ($chunks as $thisChunk) {
				if (preg_match('/^\s*$/', $thisChunk)) next;
				$numChars += strlen($thisChunk);
				if ($numChars >= $charLimit) {
					$excess = $numChars - $charLimit;
					$thisChunkIndex = strlen($thisChunk) - $excess;
					$finalChunkPiece = substr($thisChunk, 0, $thisChunkIndex);
					break;
				}
				$lastChunk = $thisChunk;
			}
			return preg_replace('/(.*?' . preg_quote($lastChunk) . '<.*?>' . preg_quote($finalChunkPiece) . ').*/s', '$1', $htmlToParse) . "…";
		}
		
		#function action_getBlogHTML() {
        public function action_getBlogHTML() {
			$currentPage = isset($_REQUEST['blogPage']) ? $_REQUEST['blogPage'] : '1';
			$tumblrFeedStart = ($currentPage - 1) * $this->postsPerPage;
			
			$i = 0;
			$contents = false;
			while(!$contents) {
				if (++$i > 10) {
					echo '<p class="tumblrfeed-error">Tried to access Tumblr Feed 10 times, but failed.  Either Tumblr is offline or the username, ' . $this->tumblrUsername . ', doesn\'t exist.</p>';
					echo '</div>';
					return false;
				}
				$contents = Loader::helper('file')->getContents('http://' . $this->tumblrUsername . '.tumblr.com/api/read/json?start=' . $tumblrFeedStart . '&num=' . $this->postsPerPage);
				if(!strpos($contents, 'tumblr_api_read')) { $contents = false; }
			}

			$contents = preg_replace('/.*?(\{.*\});/', '$1', $contents);
			$tumblr = Loader::helper('json')->decode($contents);
			$nPostsInFeed = $tumblr->{'posts-total'};
			$notFirst = false;
			
			if ($this->usePagination) {
				echo '<p class="tumblrfeed-pagination">Select a page:';
				$this->insertPagination($this->postsPerPage, $nPostsInFeed, $currentPage);
				echo '</p>';
			}
			foreach ($tumblr->{'posts'} as $post) {
				if($notFirst) { $borderClass = ' tumblrfeed-topborder'; } else { $borderClass = ''; }
				$notFirst = true;
				echo '<div class="tumblrfeed-post' . $borderClass . '">';
				$postURL = $post->{'url'};
				$postDate = $post->{'unix-timestamp'};
				if (!$this->hideTitles) {
					echo '<p class="tumblrfeed-type-title">On <a href="' . $postURL . '">' . date($this->dateFormat, $postDate) . '</a> ' . $this->tumblrUsername . ' posted this ';
				}
				switch($post->{'type'}) {
					case 'regular':
						if (!$this->hideTitles) echo 'entry:</p>';
						echo '<p class="tumblrfeed-title">' . $post->{'regular-title'} . '</p>';

						if ($this->charsPerEntry > 0) {
							$entryBody = $this->parseHtmlNumChars($post->{'regular-body'}, $this->charsPerEntry) . "&nbsp;&nbsp;<a href=\"" . $postURL . "\">Read more</a>";
						}
						else {
							$entryBody = $post->{'regular-body'};
						}

						echo $entryBody;
						break;
					case 'link':
						if (!$this->hideTitles) echo 'link:</p>';
						echo '<p class="tumblrfeed-link"><a class="tumblrfeed-link-title" href="' . $post->{'link-url'} . '">' . $post->{'link-text'} . '</a></p>';

						if ($this->charsPerEntry > 0) {
							$linkDescription = $this->parseHtmlNumChars($post->{'link-description'}, $this->charsPerEntry) . "&nbsp;&nbsp;<a href=\"" . $postURL . "\">Read more</a>";
						}
						else {
							$linkDescription = $post->{'link-description'};
						}
						
						echo $linkDescription;
						break;
					case 'quote':
						if (!$this->hideTitles) echo 'quote:</p>';
						echo '<p class="tumblrfeed-quote">' . $post->{'quote-text'} . '</p>';
						echo '<p class="tumblrfeed-quotesource">' . $post->{'quote-source'} . '</p>';
						break;
					case 'photo':
						if (!$this->hideTitles) echo 'photo:</p>';
						if ($this->preferedImageWidth > 500 & strlen($post->{'photo-url-1280'}) > 0) {
							$photoURL = $post->{'photo-url-1280'};
						} elseif ($this->preferedImageWidth > 400 & strlen($post->{'photo-url-500'}) > 0) {
							$photoURL = $post->{'photo-url-500'};
						} elseif ($this->preferedImageWidth > 250 & strlen($post->{'photo-url-400'}) > 0) {
							$photoURL = $post->{'photo-url-400'};
						} elseif ($this->preferedImageWidth > 100 & strlen($post->{'photo-url-250'}) > 0) {
							$photoURL = $post->{'photo-url-250'};
						} elseif ($this->preferedImageWidth > 75 & strlen($post->{'photo-url-100'}) > 0) {
							$photoURL = $post->{'photo-url-100'};
						} else {
							$photoURL = $post->{'photo-url-75'};
						}
						
						echo '<p class="tumblrfeed-photo"><a href="' . $postURL . '"><img src="' . $photoURL . '" alt="" width="' . $this->preferedImageWidth . '" /></a></p>';
						
						if ($this->charsPerEntry > 0) {
							$entryBody = $this->parseHtmlNumChars($post->{'photo-caption'}, $this->charsPerEntry) . "&nbsp;&nbsp;<a href=\"" . $postURL . "\">Read more</a>";
						}
						else {
							$entryBody = $post->{'photo-caption'};
						}
						
						echo '<p class="tumblrfeed-caption">' . $entryBody . '</p>';
						break;
					case 'answer':
						if (!$this->hideTitles) echo 'question and answer:</p>';
						echo '<p class="tumblrfeed-question"><span class="tumblrfeed-qatitle">Question: </span>' . $post->{'question'} . '</p>';
						echo '<p class="tumblrfeed-answer"><span class="tumblrfeed-qatitle">Answer: </span>' . $post->{'answer'} . '</p>';
						break;
					case 'conversation':
						if (!$this->hideTitles) echo 'conversation:</p>';
						echo '<p class="tumblrfeed-title"><a href="' . $postURL . '">' . $post->{'conversation-title'} . '</a></p>';
						echo '<p>This type of entry is unsupported, but you can click the title above to view it on the Tumblr website.</p>';
						break;
					case 'video':
						if (!$this->hideTitles) echo 'video:</p>';
						echo '<p class="tumblrfeed-caption">' . $post->{'video-caption'} . '</p>';
						echo '<p>This type of entry is unsupported, but you can <a href="' . $postURL . '">view it on the Tumblr website</a>.</p>';					
						break;
					case 'audio':
						if (!$this->hideTitles) echo 'audio:</p>';
						echo '<p class="tumblrfeed-caption">' . $post->{'audio-caption'} . '</p>';
						echo '<p>This type of entry is unsupported, but you can <a href="' . $postURL . '">view it on the Tumblr website</a>.</p>';					
						break;
					default:
						break;
				}
				if (strlen($this->entryURL) > 0) {
					$singlePrefix = $this->entryURL;
					
					if (!strpos($singlePrefix, '?')) {
						$singlePrefix .= '?';
					}
					
					$singleURL = $singlePrefix . 'tumblrUsername=' . $this->tumblrUsername . '&tumblrEntryID=' . $post->{'id'};
					echo '<p class="tumblrfeed-read-entry"><a href="' . $singleURL . '">Full Entry</a></p>';
				}
				echo '</div>';
			}
			if ($this->usePagination) {
				echo '<p class="tumblrfeed-pagination">Select a page:';
				$this->insertPagination($this->postsPerPage, $nPostsInFeed, $currentPage);
				echo '</p>';
			}
		
			exit;
		}
		
		function save($args) {
			$args['usePagination'] = isset($args['usePagination']) ? 1 : 0;
			$args['hideTitles'] = isset($args['hideTitles']) ? 1 : 0;
			parent::save($args);
		}
	}
	
?>