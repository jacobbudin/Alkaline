<?php

require_once('config.php');
require_once(PATH . CLASSES . 'alkaline.php');

$alkaline = new Alkaline;
$alkaline->recordStat('photo');
$alkaline->addComments();

$id = $alkaline->findID($_GET['id'], true);
if(!$id){ $alkaline->error('No photo was found.', 404); }

$photo_ids = new Find($id);
$photo_ids->privacy('public');
$photo_ids->find();

$photos = new Photo($photo_ids);
$photos->updateViews();
$photos->formatTime();
$photos->getImgUrl('medium');
$photos->getExif();
$photos->getTags();
$photos->getRights();
$photos->getComments();
$photos->hook();

$header = new Canvas;
$header->load('header');
$header->assign('Title', @$photos->photos[0]['photo_title']);
$header->display();

$index = new Canvas;
$index->load('photo');
$index->loop($photos);
$index->display();

$footer = new Canvas;
$footer->load('footer');
$footer->display();

?>