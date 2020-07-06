# JOOX API Wrapper PHP
PHP Wrapper for grabbing data from JOOX Server, inspired by : [osyduck/jooxdownloader](https://github.com/osyduck/jooxdownloader)


## Installation 
use `composer`
```
composer require rendrianarma/jooxapi-x

```
then use it
```
use JooxAPIx\JooxAPIx;

```

## Usage
Require autoload.php
```
require "vendor/autoload.php";
```


## Song by Keyword
Parameter : `$keyword`
```
$songs = JooxAPIx::songByKeyword('sayang');
print_r($songs);
```
## Song Details by Id
Parameter : `$songId`
```
$songs = JooxAPIx::songById('QWZpTFdCNGFLa2ZTRVFWb1FKM1hTQT09');
print_r($songs);
```

## Song Playlist By Album
Parameter : `$albumId`
```
$songs = JooxAPIx::songByAlbum(1924327);
print_r($songs);
```

## Download Music
Parameters : `$id` =  Song ID and `$type`= mp3 or m4a
```
$songs = JooxAPIx::downloadSong('QWZpTFdCNGFLa2ZTRVFWb1FKM1hTQT09', 'mp3');
return $songs;
```

## Credit
-------------
1. Haqny (Creator of the Script)
2. Me :v