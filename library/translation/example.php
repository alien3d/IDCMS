<?php

/* Bing translation example */

require_once('./BingTranslateLib/BingTranslate.class.php');

$gt = new BingTranslateWrapper('YOUR_BING_APPID');

/* Text to translate */
$text = 'hello world';

/* Translate from "English" to "French" */
$translated_text = $gt->translate($text, "en", "fr");

echo $translated_text;


?>


