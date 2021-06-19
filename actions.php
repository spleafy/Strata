<?php
    if (isset($_REQUEST['languageRequest']) && $_REQUEST['languageRequest'] == 'bg') {
        $languageBg = file_get_contents("strata-bg.html");

        header('Content-Type: application/json');
        echo json_encode($languageBg);
    } else if (isset($_REQUEST['languageRequest']) && $_REQUEST['languageRequest'] == 'en') {
        $languageEn = file_get_contents("strata-en.html");

        header('Content-Type: application/json');
        echo json_encode($languageEn);
    }
?>

