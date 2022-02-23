<?php /** @noinspection PhpUndefinedVariableInspection */
defined('C5_EXECUTE') or die("Access Denied.");

echo '<p>Choose your options below to setup the Tumblr Entry block.</p>';

echo $form->label('preferedImageWidth', 'Image width for photo posts:');
echo $form->text('preferedImageWidth', '500', array('style' => 'width: 100px'));

echo $form->label('dateFormat', 'Format for posting dates:');
echo $form->text('dateFormat', 'jS F Y \a\t H:i', array('style' => 'width: 320px'));


