<?php

require_once('./../../config.php');
require_once(PATH . CLASSES . 'alkaline.php');
require_once(PATH . CLASSES . 'photo.php');
require_once(PATH . CLASSES . 'import.php');
require_once(PATH . CLASSES . 'user.php');

$alkaline = new Alkaline;
$user = new User;

$user->perm(true);

if(empty($_POST['photo_file'])){
	$photo_files = $alkaline->seekPhotos(PATH . SHOEBOX);
	echo json_encode($photo_files);
}
else{
	$photo = new Import($_POST['photo_file']);
	echo json_encode($photo->photo_ids[0]);
}

?>