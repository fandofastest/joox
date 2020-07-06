<?php
namespace JooxAPIx;
set_time_limit(0);
ignore_user_abort(1);



class JooxAPIx
{
  public function __construct()
  {
  }


  public static function songByKeyword($keyword)
  {
    $result = [];





    $ch = curl_init('http://api.joox.com/web-fcgi-bin//web_search?callback=mutiara&lang=id&country=id&type=0&search_input=' . rawurlencode(trim($keyword)) . '&pn=1&sin=0&ein=29&_=' . time());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
    $json = curl_exec($ch);
    curl_close($ch);
    $json = str_replace('mutiara(', '', $json);
    $json = str_replace(')', '', $json);
    $json = json_decode($json);
    if ($json->direct === 2) {
      $result['thumbNail'] = $json->bigpic;
      $result['songs'] = [];

      foreach ($json->itemlist as $itemList) {

        array_push($result['songs'], [

          'id' => base64_encode($itemList->songid),
          'singerId' =>  $itemList->singerid,
          'singerName' =>  base64_decode($itemList->info2),
          'title' => base64_decode($itemList->info1),
          'albumId' => $itemList->albumid,
          'albumName' => base64_decode($itemList->info3),
          'duration' => gmdate('i:s', $itemList->playtime)


        ]);
      }

      return $result;
    }
  }

  public static function songById($id)
  {

    $result = [];
    $lyric = self::getSongLyric($id);
    $ch = curl_init('https://api.joox.com/web-fcgi-bin/web_get_songinfo?songid=' . base64_decode(trim($id)) . '&lang=id&country=id&from_type=null&channel_id=null&_=' . time());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-For: 36.73.34.109"));
    curl_setopt($ch, CURLOPT_COOKIE, 'wmid=142420656; user_type=1; country=id; session_key=2a5d97d05dc8fe238150184eaf3519ad;');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
    $json = curl_exec($ch);

    $json = str_replace('MusicInfoCallback(', '', $json);
    $json = str_replace(')', '', $json);
    $json = json_decode($json);

    array_push($result, [
      'songName' => $json->msong,
      'singerName' => $json->msinger,
      'thumbNail' => $json->imgSrc,
      'albumThumbNail' => $json->album_url,
      'kbpsMap' => $json->kbps_map,
      'downloadLinks' => [
        'mp3' => $json->mp3Url,
        'm4a' => $json->m4aUrl,
        'r192' => $json->r192Url,
        'r320' => $json->r320Url,
      ],
      'lyric' => $lyric

    ]);
    return $result;
  }

  public static function getSongLyric($id)
  {
    $ch = curl_init('http://api.joox.com/web-fcgi-bin/web_lyric?musicid=' . base64_decode(trim($id)) . '&lang=id&country=id&_=' . time());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-For: 36.73.34.109"));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
    $ly = curl_exec($ch);
    curl_close($ch);
    $ly = str_replace('MusicJsonCallback(', '', $ly);
    $ly = str_replace(')', '', $ly);
    $ly = json_decode($ly);

    $ly = str_replace('[999:00.00]***Lirik didapat dari pihak ketiga***', '***Recoded By J***', base64_decode($ly->lyric));
    $ly = str_replace('[offset:0]', '', $ly);
    return $ly;
  }

  public static function songByArtist($artisId)
  {
    $result = [];
    $ch = curl_init('http://api.joox.com/web-fcgi-bin/web_album_singer?cmd=2&singerid=' . trim($artisId) . '&sin=0&ein=29&lang=id&country=id&callback=mutiara&_=' . time());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
    $json = curl_exec($ch);
    curl_close($ch);
    $json = str_replace('mutiara(', '', $json);
    $json = str_replace(')', '', $json);
    $json = json_decode($json);
    if (!$json->name) {
      return 'No Result';
    }

    $result['thumbNail'] = $json->pic;
    $result['songs'] = [];

    foreach ($json->songlist as $itemList) {

      array_push($result['songs'], [

        'id' => base64_encode($itemList->songid),
        'singerId' =>  $itemList->singerid,
        'singerName' =>  base64_decode($itemList->singername),
        'title' => base64_decode($itemList->songname),
        'albumId' => $itemList->albumid,
        'albumName' => base64_decode($itemList->albumname),
        'duration' => gmdate('i:s', $itemList->playtime),
        'kbpsMap' => $itemList->kbps_map,
        'downloadLinks' => [
          'url24' => $itemList->url24,
          'url48' => $itemList->url48,
          'url96' => $itemList->url96,
          'url128' => $itemList->url128,
          'url128' => $itemList->url320,
        ],



      ]);
    }

    return $result;
  }


  public static function songByAlbum($albumId)
  {
    $result = [];
    $ch = curl_init('http://api.joox.com/web-fcgi-bin/web_get_albuminfo?albumid=' . trim($albumId) . '&lang=id&country=id&from_type=null&channel_id=null&_=' . time());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
    $json = curl_exec($ch);
    curl_close($ch);
    $jsonx = json_decode($json);
    if (!$jsonx->albuminfo) {
      return 'No Result';
    }

    $json = $jsonx->albuminfo;


    $result['albumId'] = $albumId;
    $result['thumbNail'] = $json->album_url;
    $result['songs'] = [];

    foreach ($json->songlist as $itemList) {

      array_push($result['songs'], [

        'id' => base64_encode($itemList->songid),
        'singerId' =>  $itemList->singerid,
        'singerName' =>  base64_decode($itemList->singername),
        'title' => base64_decode($itemList->songname),
        'albumId' => $itemList->albumid,
        'albumName' => base64_decode($itemList->albumname),
        'duration' => gmdate('i:s', $itemList->playtime),
        'kbpsMap' => $itemList->kbps_map,
        'downloadLinks' => $itemList->url



      ]);
    }

    return $result;
  }

  public static function downloadSong($id, $type = 'm4a')
  {

    if (trim($type) === 'm4a') {
      return  self::m4aDownload($id);
    } else {
      return self::mp3Download($id);
    }
  }

  public static function m4aDownload($id)
  {
    $ch = curl_init('http://api.joox.com/web-fcgi-bin/web_get_songinfo?songid=' . base64_decode($id) . '&lang=id&country=id&from_type=null&channel_id=null&_=' . time());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIE, 'wmid=142420656; user_type=1; country=id; session_key=2a5d97d05dc8fe238150184eaf3519ad;');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
    $json = curl_exec($ch);

    $json = str_replace('MusicInfoCallback(', '', $json);
    $json = str_replace(')', '', $json);
    $json = json_decode($json);


    $fi = $json->m4aUrl;
    $sing = $json->msinger;
    $song = $json->msong;

    header("Content-type: application/x-file-to-save");
    header('Content-Disposition: attachment; filename="' . $song . ' - ' . $sing . '.m4a"');
    readfile($fi);
  }
  public static function mp3Download($id)
  {

    $ch = curl_init('http://api.joox.com/web-fcgi-bin/web_get_songinfo?songid=' . base64_decode($id) . '&lang=id&country=id&from_type=null&channel_id=null&_=' . time());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIE, 'wmid=142420656; user_type=1; country=id; session_key=2a5d97d05dc8fe238150184eaf3519ad;');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
    $json = curl_exec($ch);

    $json = str_replace('MusicInfoCallback(', '', $json);
    $json = str_replace(')', '', $json);
    $json = json_decode($json);
    $fi = $json->mp3Url;
    $sing = $json->msinger;
    $song = $json->msong;
    /*	echo '<pre>';
	print_r($json);
	echo '</pre>'; */
    header("Content-type: application/x-file-to-save");
    header('Content-Disposition: attachment; filename="' . $song . ' - ' . $sing . '.mp3"');
    readfile($fi);
  }


  public static function songDetails($id)
  {
  }
}
