<?php

// header('Content-Type: application/xml; charset=utf-8');

// Channel Settings

$channelDirectory = "./assets/podcasts/channel";

$channel = fopen("./assets/podcasts/channel/channel_settings.txt", "r");

$channelTitle = fgets($channel);

$channelPictureUrl = "";

fclose($channel);

$channelDescriptionFile = file_get_contents("./assets/podcasts/channel/channel_settings.txt");

$channelDescription = getTemplateReplace($channelDescriptionFile, array(
  $channelTitle => "" 
));

// Podcast Settings

$podcast_directory = "./assets/podcasts";

$file_array = array();

$podcast_html = "";

$podcastItemHtml = "";

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
  $protocol = "https";
} else {
  $protocol = "http";
}

$host = $_SERVER["HTTP_HOST"];

$podcastFileDir = "/assets/podcasts/";

function getTemplateReplace($_text, $_replace_array = []) {

  foreach($_replace_array as $from => $to) {
    $_text = str_replace($from, $to, $_text);
  }

  return $_text;

}

foreach(glob($podcast_directory.'/*.*') as $file) {
  $file_exploded =  explode(".", $file);
  if ($file_exploded[2] == "mp3") {
    $file_array[] = $file;
  }
}

foreach($file_array as $mp3) {
  $mp3_array = explode(".", $mp3);

  // Checking If File Exists And Setting Default Values If They Don't

  $mp3_name = ".".$mp3_array[1].".mp3";
  if (!file_exists($mp3_name)) {
    $mp3_name = "./assets/default/default.mp3";
  }

  $txt_name = ".".$mp3_array[1].".txt";
  if (!file_exists($txt_name)) {
    $txt_name = "./assets/default/default.txt";
  }

  $img_name = ".".$mp3_array[1].".png";
  if (!file_exists($img_name)) {
    $img_name = "./assets/default/default.png";
  }

  $text_file = fopen($txt_name, "r");

  $person = fgets($text_file);

  $position = fgets($text_file);

  fclose($text_file);

  $description_file = file_get_contents($txt_name);

  $description = getTemplateReplace($description_file, array(
    $person => "",
    $position => ""
  )); 

  $podcastXmlItemFile = file_get_contents("item.html");

  $podcastDate = date("F d Y H:i:s.", filemtime($mp3_name));

  $mp3FileNameExploded = explode("/", $mp3_name);

  $mp3FileName = $mp3FileNameExploded[sizeof($mp3FileNameExploded) - 1];

  $link = $protocol."://".$host.$podcastFileDir.$mp3FileName;

  $podcastHTMLItem = getTemplateReplace($podcastXmlItemFile, array(
    "{TITLE}" => $person,
    "{PUBLISHDATE}" => $podcastDate,
    "{URL_PODCAST}" => $link,
    "{PODCAST_DESCRIPTION}" => $description
  ));

  $podcastItemHtml .= $podcastHTMLItem;
}

foreach(glob($channelDirectory.'/*.*') as $channelFile) {
  $channelFileExploded = explode(".", $channelFile);
  if ($channelFileExploded[2] == "png" || $channelFileExploded[2] == "jpg" || $channelFileExploded[2] == "jpeg") {
    $channelPictureFileNameExploded = explode("/", $channelFile);
    $channelPictureFileName = $channelPictureFileNameExploded[sizeof($channelPictureFileNameExploded) - 1];
    $channelPictureUrl = $protocol."://".$host."/assets/podcasts/channel/".$channelPictureFileName;
    echo $channelPictureUrl;
  }
}

if (!file_exists($channelPictureUrl)) {
  $channelPictureUrl = $protocol."://".$host."/assets/default/default.png";
}

$channelUrl = $protocol."://".$host;

$podcastXmlFile = file_get_contents("xml.html");

$podcastXmlHtml = getTemplateReplace($podcastXmlFile, array(
  "{TITLE}" => $channelTitle,
  "{URL_GLOBAL}" => $channelUrl,
  "{DESCRIPTION}" => $channelDescription,
  "{IMG_URL}" => $channelPictureUrl,
  "{ITEMS}" => $podcastItemHtml
));

echo $podcastXmlHtml;

?>