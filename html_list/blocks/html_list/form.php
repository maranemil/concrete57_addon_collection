<?php

namespace Concrete\Package\HtmlList\Block\HtmlList;
use UserInfo;
use Loader;
use Config;
use Page;
use View;

defined('C5_EXECUTE') or die("Access Denied.");

$ih = Loader::helper('concrete/ui'); ?>

<div id="ccm-textPane-add" class="ccm-textPane">

	<div class="clearfix"><?php 
		echo $form->label('title', t('Title')); ?>
		<div class="input"><?php 
			echo $form->text('title', $title); ?>
		</div>
	</div>
	<div id="list" class="clearfix"><?php 
		echo $form->label('items', t('Items'));
		echo $form->hidden('list', $list); ?>
		<div class="input">
			<div id="items"><?php 
				foreach($items as $item) : ?>
					<div class="item" style="margin-bottom:14px;"><?php 
						echo $form->hidden('item', $item);
						echo $form->text('text', $item, array('disabled'=>'disabled'));
						echo $ih->button_js(t('Edit'), 'javascript:list_block_edit(this);', 'left', 'edit', array('style'=>'margin-left:10px;'));
						echo $ih->button_js(t('Remove'), 'javascript:list_block_remove(this);', 'left', 'remove', array('style'=>'margin-left:10px;'));
						echo $ih->button_js(t('Update'), 'javascript:list_block_save(this);', 'left', 'save', array('style'=>'margin-left:10px;display:none;'));
						echo $ih->button_js(t('Cancel'), 'javascript:list_block_cancel(this);', 'left', 'cancel', array('style'=>'margin-left:10px;display:none;')); ?>
					</div><?php 
				endforeach; ?>
			</div>
			<div id="item-example" class="item" style="display:none;margin-bottom:14px;"><?php 
				echo $form->hidden('item', "");
				echo $form->text('text', "", array('disabled'=>'disabled'));
				echo $ih->button_js(t('Edit'), 'javascript:list_block_edit(this);', 'left', 'edit', array('style'=>'margin-left:10px;'));
				echo $ih->button_js(t('Remove'), 'javascript:list_block_remove(this);', 'left', 'remove', array('style'=>'margin-left:10px;'));
				echo $ih->button_js(t('Update'), 'javascript:list_block_save(this);', 'left', 'save', array('style'=>'margin-left:10px;display:none;'));
				echo $ih->button_js(t('Cancel'), 'javascript:list_block_cancel(this);', 'left', 'cancel', array('style'=>'margin-left:10px;display:none;')); ?>
			</div>
			<div id="new"><?php 
				echo $form->text('add', $add);
				echo $ih->button_js(t('Add'), 'javascript:list_block_add();', 'left', 'add', array('style'=>'margin-left:10px;')); ?>
			</div>
		</div>
	</div>

</div>