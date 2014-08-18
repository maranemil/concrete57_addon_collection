<?php

namespace Concrete\Package\TumblrFeed\Block\TumblrEntry;
use \Concrete\Core\Block\BlockController;
use UserInfo;
use Loader;
use Config;
use Page;
use View;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController {

/**
 * An object used by the TumbrFeed Block to display entries of a Tumblr account
 *
 * @package Blocks
 * @subpackage BlockTypes
 * @author Stuart McHattie <stuart.mchattie@gmail.com>
 * @category Concrete
 * @copyright  Copyright (c) 2011 Stuart McHattie. (http://www.sdjmchattie.com)
 *
 */

	#class TumblrEntryBlockController extends BlockController {
		
		var $pobj;
		
		protected $btDescription = "Allows the insertion of a single Tumblr entry onto your site.";
		protected $btName = "Tumblr Entry";
		protected $btTable = 'btTumblrEntry';
		protected $btInterfaceWidth = "350";
		protected $btInterfaceHeight = "350";
		
		function action_getEntryHTML() {
			if (!isset($_REQUEST['tumblrUsername']) || !isset($_REQUEST['tumblrEntryID'])) {
				exit;
			}
			
			$tumblrUsername = $_REQUEST['tumblrUsername'];
			$entryToDisplay = $_REQUEST['tumblrEntryID'];
			
			$i = 0;
			$contents = false;
			while(!$contents) {
				if (++$i > 10) {
					echo '<p class="tumblrentry-error">Tried to access Tumblr Feed 10 times, but failed.  Either Tumblr is offline or the post ID ' . $entryToDisplay . ' for username, ' . $tumblrUsername . ', doesn\'t exist.</p>';
					echo '</div>';
					return false;
				}
				$contents = Loader::helper('file')->getContents('http://' . $tumblrUsername . '.tumblr.com/api/read/json?id=' . $entryToDisplay);
				if(!strpos($contents, 'tumblr_api_read')) { $contents = false; }
			}

			$contents = preg_replace('/.*?(\{.*\});/', '$1', $contents);
			$tumblr = Loader::helper('json')->decode($contents);
			
			foreach ($tumblr->{'posts'} as $post) {
				echo '<div class="tumblrentry-post' . $borderClass . '">';
				$postURL = $post->{'url'};
				$postDate = $post->{'unix-timestamp'};
				echo '<p class="tumblrentry-type-title"><a href="' . $postURL . '">' . date($this->dateFormat, $postDate) . '</a></p>';
				
				switch($post->{'type'}) {
					case 'regular':
						echo '<p class="tumblrentry-title">' . $post->{'regular-title'} . '</p>';

						$entryBody = $post->{'regular-body'};

						echo $entryBody;
						break;
					case 'link':
						echo '<p class="tumblrentry-link"><a class="tumblrentry-link-title" href="' . $post->{'link-url'} . '">' . $post->{'link-text'} . '</a></p>';
						
						$linkDescription = $post->{'link-description'};

						echo $linkDescription;
						break;
					case 'quote':
						echo '<p class="tumblrentry-quote">' . $post->{'quote-text'} . '</p>';
						echo '<p class="tumblrentry-quotesource">' . $post->{'quote-source'} . '</p>';
						break;
					case 'photo':
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
						
						echo '<p class="tumblrentry-photo"><a href="' . $postURL . '"><img src="' . $photoURL . '" alt="" width="' . $this->preferedImageWidth . '" /></a></p>';
						
						$entryBody = $post->{'photo-caption'};
						
						echo '<p class="tumblrentry-caption">' . $entryBody . '</p>';
						break;
					case 'answer':
						echo '<p class="tumblrentry-question"><span class="tumblrentry-qatitle">Question: </span>' . $post->{'question'} . '</p>';
						echo '<p class="tumblrentry-answer"><span class="tumblrentry-qatitle">Answer: </span>' . $post->{'answer'} . '</p>';
						break;
					case 'conversation':
						echo '<p class="tumblrentry-title"><a href="' . $postURL . '">' . $post->{'conversation-title'} . '</a></p>';
						echo '<p>This conversation can be <a href="' . $postURL . '">read on Tumblr</a>.</p>';
						break;
					case 'video':
						echo '<p class="tumblrentry-caption">' . $post->{'video-caption'} . '</p>';
						echo '<p>This video can be <a href="' . $postURL . '">viewed on Tumblr</a>.</p>';					
						break;
					case 'audio':
						echo '<p class="tumblrentry-caption">' . $post->{'audio-caption'} . '</p>';
						echo '<p>This audio recording can be <a href="' . $postURL . '">listened to on Tumblr</a>.</p>';					
						break;
					default:
						break;
				}
				echo '</div>';
			}
		
			exit;
		}
		
		function save($args) {
			parent::save($args);
		}
	}
	
?>