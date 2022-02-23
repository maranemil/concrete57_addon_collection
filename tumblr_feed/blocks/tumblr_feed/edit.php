<?php /** @noinspection PhpUndefinedVariableInspection */
defined('C5_EXECUTE') or die("Access Denied.");

echo '<p>Choose your options below to edit the Tumblr Feed block.</p>';

echo '<p>';
echo $form->label('blockTitle', 'Title for block:');
echo $form->text('blockTitle', $blockTitle, array('style' => 'width: 320px'));
echo '</p>';

echo '<p>';
echo $form->label('tumblrUsername', 'Tumblr Username:');
echo $form->text('tumblrUsername', $tumblrUsername, array('style' => 'width: 320px'));
echo '</p>';

echo '<p>';
echo $form->label('postsPerPage', 'Posts to display on each page:');
echo $form->text('postsPerPage', $postsPerPage, array('style' => 'width: 35px'));
echo '</p>';

echo '<p>';
echo $form->label('charsPerEntry', 'Limit entries to number of characters (0 = no limit):');
echo $form->text('charsPerEntry', $charsPerEntry, array('style' => 'width: 35px'));
echo '</p>';

echo '<p>';
echo $form->label('preferedImageWidth', 'Image width for photo posts:');
echo $form->text('preferedImageWidth', $preferedImageWidth, array('style' => 'width: 100px'));
echo '</p>';

echo '<p>';
echo $form->label('dateFormat', 'Format for posting dates:');
echo $form->text('dateFormat', $dateFormat, array('style' => 'width: 320px'));
echo '</p>';

echo '<p>';
echo $form->label('hideTitles', 'Hide date/time/username for each entry?:');
echo $form->checkbox('hideTitles', '1', $hideTitles === '1');
echo '</p>';

echo '<p>';
echo $form->label('usePagination', 'Display pagination links?:');
echo $form->checkbox('usePagination', '1', $usePagination === '1');
echo '</p>';

echo '<p>';
echo $form->label('entryURL', 'URL with Tumblr Entry Block (blank = no link):');
echo $form->text('entryURL', $entryURL, array('style' => 'width: 320px'));
echo '</p>';


