<?php

namespace Concrete\Package\HtmlPlus\Block\HtmlPlus;
use UserInfo;
use Concrete\Block\PageList;
use Loader;
use Config;
use Page;
use View;
use \Concrete\Core\Form\Service\Widget\PageSelector;
use \Concrete\Package\HtmlPlus\Block\HtmlPlus\Controller as HtmlPlusController;

defined('C5_EXECUTE') or die("Access Denied.");

	#$pageSelector = Loader::helper('form/page_selector');
    $hpc = new HtmlPlusController();
    $htmlElements = $hpc->getHtmlElements();

    #$usrSelector = Loader::helper('form/user_selector');
	#$fileSelector = Loader::helper('concrete/asset_library');
    #$ih = Loader::helper('concrete/ui');
    #$form = Loader::helper('form');
    #$html = Loader::helper('html');
    #$url = Loader::helper('concrete/urls');

?>

<!-- Load CSS here until I find a reliable Ajax way of doing it -->
<link href="<?php echo "/packages/html_plus/blocks/html_plus/styles/"; ?>/codemirror.css" rel="stylesheet" type="text/css">
<link href="<?php echo "/packages/html_plus/blocks/html_plus/styles/"; ?>/style.css" rel="stylesheet" type="text/css">

<script>
	var htmlEditor;

	(function() {
		$("#ccm-dialog-loader-wrapper").show();
		//Load the codemirror.js and execute everything after sucessful loading
		$.getScript("/packages/html_plus/blocks/html_plus/scripts/codemirror.js", function() {

			//Initialise codemirror with some options
			//For a list of availble options see http://codemirror.net/2/doc/manual.html
			htmlEditor = CodeMirror.fromTextArea(document.getElementById("htmlPlusContent"), {
				mode: 'text/html',
				lineNumbers: true,
				smartIndent: false
			});

			//Re-size editor on first load
			resizeEditor();
						
			//Re-size editor after dialog is re-sized or data is pasted into it
			$(".ui-resizable").on("resizestop", function(){
				resizeEditor();
			});
			
			$("#ccm-dialog-loader-wrapper").hide();
				
			//Clicking on a quick-tag
			$(".html-element").click(function(e){
			
				e.preventDefault();
				var elem = this;
				var highlighted = htmlEditor.getSelection();
				var data = elem.getAttribute('data-tag');
				
				//If something is highlighted wrap in the new tags where possible
				if(htmlEditor.somethingSelected()) {
					var newData = data.replace("><",">"+highlighted+"<");
					htmlEditor.replaceSelection(newData, "end");
				}else{
					htmlEditor.replaceSelection(data, "end");
				}
				htmlEditor.focus();
				
			});	
			
			//Line wrapping toggle	
			$('a#wraptoggle').toggle(function() {
				$(this).text('Unwrap');
					htmlEditor.setOption('lineWrapping', true);
					htmlEditor.refresh();
					htmlEditor.focus();
			}, function () {
				$(this).text('Wrap');
					htmlEditor.setOption('lineWrapping', false);
					htmlEditor.refresh();
					htmlEditor.focus();
			});
		
		});
					
	})();
	
	//Resize editor
	function resizeEditor(){
		var optsHeight = $("#htmlEditor-opts").height();
		var modalHeight = $(".ui-resizable").height();

		// 130px allows for the modal header and footer			
		var containerHeight = (modalHeight - optsHeight - 130) + "px";

		htmlEditor.setSize('100%', containerHeight);
		htmlEditor.refresh();
		htmlEditor.focus();
	}
	
	//Function called by C5 page selector on select
	function insertLink(pageId, pageName){
		
		var highlighted = htmlEditor.getSelection();
		if(htmlEditor.somethingSelected()) {
			htmlEditor.replaceSelection('<a href="index.php?cID='+pageId+'">'+highlighted+'</a>', "end");
		}else{
			htmlEditor.replaceSelection('<a href="index.php?cID='+pageId+'">'+pageName+'</a>', "end");
		}
		htmlEditor.focus();
	} 
</script>

<div id="htmlEditor-opts">
		
		<div class="html-element-group">
			<strong><?php  print t("Wrap long lines") ?>: <strong><br>
			<a class="wrap-toggle" id="wraptoggle" href="#"><?php  print t("Wrap") ?></a>
		</div>
		
		<div class="html-element-group" style="width:150px;">
			<strong><?php  print t("Page link") ?></strong><br>
			<?php  //print $pageSelector->selectPage('pageId', 0, 'insertLink'); ?>
            <?php
            $ps = new PageSelector();
            print $ps->selectPage('pageId', 0, 'insertLink');
            ?>
		</div>
		
		<?php
        if(is_array($htmlElements)){
            foreach($htmlElements as $group => $tag) { ?>
            <div class="html-element-group">
                <strong><?php  print $group ?></strong><br>
                <?php  foreach($tag as $name => $html) { ?>
                <a class="html-element" href="#" draggable="false"
                   data-name="<?php  print $name ?>"
                   data-tag="<?php  print h($html, ENT_COMPAT, APP_CHARSET); ?>">
                    <?php  print h($name, ENT_COMPAT, APP_CHARSET); ?></a>
                <?php  } ?>
            </div>
		<?php  }
        }?>
</div>
<div id="htmlEditor-container">
    <!-- The text area is hidden because Codemirror will populate it from the editor interface -->
    <textarea id="htmlPlusContent" name="htmlPlusContent" style="display:none;">
        <?php
        #$htmlPlusContent = $hpc->processContent();
        #print h($htmlPlusContent, ENT_COMPAT, APP_CHARSET);
        #print h($htmlPlusContent);
        #var_dump($htmlPlusContent);
        $objArray = (array) $htmlPlusContent;
        echo $objArray["htmlPlusContent"];
        ?>
    </textarea>
</div>