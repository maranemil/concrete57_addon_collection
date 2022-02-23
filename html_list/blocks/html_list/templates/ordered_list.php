<?php defined('C5_EXECUTE') or die("Access Denied.");
if (0 < strlen($title)) : ?>
    <h2><?php echo $title; ?></h2><?php
endif;
if (0 < count($items)) : ?>
    <ol><?php
    foreach ($items as $item) : ?>
        <li><?php echo $item; ?></li><?php
    endforeach; ?>
    </ol><?php
endif; ?>