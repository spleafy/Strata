<?php
    if (isset($_REQUEST['languageRequest']) && $_REQUEST['languageRequest'] == 'bg') {

        header('Content-Type: application/json');

        $languageBg = file_get_contents("strata-bg.html");

        $log_directory = "./assets/podcasts";

        $file_array = array();

        $podcast_box_file = file_get_contents("podcast-box.html");

        $podcast_html = "";

        function getTemplateReplace($_text, $_replace_array = []) {

            foreach($_replace_array as $from=>$to) {
                $_text = str_replace($from, $to, $_text);
            }
    
            return $_text;
    
        }

        foreach(glob($log_directory.'/*.*') as $file) {
            $file_exploded =  explode(".", $file);
            if ($file_exploded[2] == "mp3") {
                $file_array[] = $file;
            }
        }

        foreach($file_array as $mp3) {
            $mp3_array = explode(".", $mp3);

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

            $podcast_box_html = getTemplateReplace($podcast_box_file, array(
                "{IMAGE}" => $img_name,
                "{PERSON}" => $person,
                "{POSITION}" => $position,
                "{DESCRIPTION}" => $description,
                "{AUDIO_FILE}" => $mp3_name
            ));
            
            $podcast_html .= $podcast_box_html;
        }

        $languageBg = getTemplateReplace($languageBg, array(
            "{PODCASTS}" => $podcast_html
        ));

        echo json_encode($languageBg);

        die;

    } else if (isset($_REQUEST['languageRequest']) && $_REQUEST['languageRequest'] == 'en') {

        header('Content-Type: application/json');

        $languageEn = file_get_contents("strata-en.html");

        $log_directory = "./assets/podcasts";

        $file_array = array();

        $podcast_box_file = file_get_contents("podcast-box.html");

        $podcast_html = "";

        function getTemplateReplace($_text, $_replace_array = []) {

            foreach($_replace_array as $from=>$to) {
                $_text = str_replace($from, $to, $_text);
            }
    
            return $_text;
    
        }

        foreach(glob($log_directory.'/*.*') as $file) {
            $file_exploded =  explode(".", $file);
            if ($file_exploded[2] == "mp3") {
                $file_array[] = $file;
            }
        }

        foreach($file_array as $mp3) {
            $mp3_array = explode(".", $mp3);

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

            $podcast_box_html = getTemplateReplace($podcast_box_file, array(
                "{IMAGE}" => $img_name,
                "{PERSON}" => $person,
                "{POSITION}" => $position,
                "{DESCRIPTION}" => $description,
                "{AUDIO_FILE}" => $mp3_name
            ));
            
            $podcast_html .= $podcast_box_html;
        }

        $languageEn = getTemplateReplace($languageEn, array(
            "{PODCASTS}" => $podcast_html
        ));

        echo json_encode($languageEn);

        die;
    }
?>

