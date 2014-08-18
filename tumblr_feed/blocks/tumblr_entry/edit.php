<?php    
	defined('C5_EXECUTE') or die("Access Denied.");
	
	echo '<p>Choose your options below to edit the Tumblr Entry block.</p>';
	
	echo $form->label('preferedImageWidth', 'Image width for photo posts:');
	echo $form->text('preferedImageWidth', $preferedImageWidth, array('style' => 'width: 100px'));	

	echo $form->label('dateFormat', 'Format for posting dates:');
	echo $form->text('dateFormat', $dateFormat, array('style' => 'width: 320px'));	

?>
