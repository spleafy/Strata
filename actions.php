<?php 

  function getTemplateReplace($_text, $_replace_array = []) {

    foreach($_replace_array as $from=>$to) {
        $_text = str_replace($from, $to, $_text);
    }

    return $_text;

  }

  $languageData = file_get_contents("text.json");

  $text = json_decode($languageData);

  if (isset($_REQUEST['languageRequest']) && $_REQUEST['languageRequest'] == 'bg') {

      header('Content-Type: application/json');

      $languageBg = file_get_contents("strata.html");

      // Variable For The Show Id *Change It If The Show Id Is Changed*
      $showId = 22506; 
      // Variable For The Api Key *Change It If The Key Is Changed*
      $APIKey = "JR3JgX9ri_Gy5p0WC8sTwg";

      $requestURL = "https://api.transistor.fm/v1/episodes?show_id=".$showId."&status=published&pagination[per]=100";

      $httpArray = array(
        'http' =>
          array(
              'method'  => 'GET',
              'header'  => array(
                  "x-api-key: $APIKey",
                  'Content-type: application/json'
              ),
          )
      );

      $context = stream_context_create($httpArray);
      
      $data = file_get_contents($requestURL, false, $context);

      $podcastObj = json_decode($data);
      
      $podcast_box_file = file_get_contents("podcast-box.html");

      $podcast_panel = file_get_contents("podcast-panel.html");

      $podcast_html = "";

      foreach($podcastObj->data as $podcast) {

        if ($podcast->attributes->image_url == null) {
            $podcastImage = "./assets/default/default.png";
        } else {
            $podcastImage = $podcast->attributes->image_url;
        }

        if ($podcast->attributes->summary == "") {
            $podcastSummary = file_get_contents("./assets/default/default.txt");
        } else {
            $podcastSummary = $podcast->attributes->summary;
        }

        $podcast_box_html = getTemplateReplace($podcast_box_file, array(
            "{IMAGE}" => $podcastImage,
            "{TITLE}" => $podcast->attributes->title,
            "{EPISODE}" => $podcast->attributes->number,
            "{DESCRIPTION}" => $podcastSummary,
            "{AUDIO_FILE}" => $podcast->attributes->media_url
        ));
        
        $podcast_html .= $podcast_box_html;
      }

      $podcast_panel = getTemplateReplace($podcast_panel, array(
        "{PODCASTS}" => $podcast_html,
    ));

      $languageBg = getTemplateReplace($languageBg, array(
          "{PODCASTS}" => $podcast_panel,
          "{HEADER_ABOUT_LINK}" => $text->bg->header->about_link,
          "{HEADER_LANGUAGE_FIRST}" => $text->bg->header->language->first,
          "{HEADER_LANGUAGE_SECOND}" => $text->bg->header->language->second,
          "{HEADER_ACTION}" => $text->bg->header->action,
          "{HEADER_HEADING}" => $text->bg->header->heading,
          "{HEADER_SUB_HEADING}" => $text->bg->header->sub_heading,
          "{HEADER_PARAGRAPH}" => $text->bg->header->paragraph,
          "{PODCAST_HEADING}" => $text->bg->podcast->heading,
          "{PODCAST_SUB_HEADING}" => $text->bg->podcast->sub_heading,
          "{PODCAST_PARAGRAPH}" => $text->bg->podcast->paragraph,
          "{BRANDS_HEADING}" => $text->bg->brands->heading,
          "{CONTACT_HEADING}" => $text->bg->contact->heading,
          "{CONTACT_MAIL}" => $text->bg->contact->mail,
      ));

      echo json_encode($languageBg);

      die;

    } else if (isset($_REQUEST['languageRequest']) && $_REQUEST['languageRequest'] == 'en') {

        header('Content-Type: application/json');

        $languageEn = file_get_contents("strata.html");

        $languageEn = getTemplateReplace($languageEn, array(
          "{PODCASTS}" => "",
          "{HEADER_ABOUT_LINK}" => $text->en->header->about_link,
          "{HEADER_LANGUAGE_FIRST}" => $text->en->header->language->first,
          "{HEADER_LANGUAGE_SECOND}" => $text->en->header->language->second,
          "{HEADER_ACTION}" => $text->en->header->action,
          "{HEADER_HEADING}" => $text->en->header->heading,
          "{HEADER_SUB_HEADING}" => $text->en->header->sub_heading,
          "{HEADER_PARAGRAPH}" => $text->en->header->paragraph,
          "{BRANDS_HEADING}" => $text->en->brands->heading,
          "{CONTACT_HEADING}" => $text->en->contact->heading,
          "{CONTACT_MAIL}" => $text->en->contact->mail,
      ));

        echo json_encode($languageEn);

        die;
    }
?>