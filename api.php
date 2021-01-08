<?php 


use JooxAPIx\JooxAPIx;
require "vendor/autoload.php";


$method=$_GET['method'];



if ($method=='cari') {
    
$songs = JooxAPIx::songByKeyword($_GET['q']);
echo json_encode($songs);
    # code...
}


elseif ($method=='play') {
    $songs = JooxAPIx::downloadSong($_GET['id'], 'mp3');
    return $songs;
    # code...
}

elseif ($method=='albumdetail') {
    $songs = JooxAPIx::songByAlbum($_GET['id']);
    echo json_encode($songs);
    # code...
}

elseif ($method=='song') {
    $songs = JooxAPIx::songById($_GET['id']);
    echo json_encode($songs);

    # code...
}

elseif ($method=='getimage') {
    $songs = JooxAPIx::getImgUrlById($_GET['id']);
    header('Location: '.$songs);

    # code...
}




?>