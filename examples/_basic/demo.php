<?php

/**
 * TEXY! BASIC DEMO
 * --------------------------------------
 *
 * This source file is subject to the GNU GPL license.
 *
 * @link       http://texy.info/
 * @author     David Grudl aka -dgx- <dave@dgx.cz>
 * @copyright  Copyright (c) 2004-2007 David Grudl
 * @license    GNU GENERAL PUBLIC LICENSE v2
 */


// include Texy!
require_once dirname(__FILE__).'/../../texy/texy.php';



$texy = new Texy();

// other OPTIONAL configuration
$texy->encoding = 'windows-1250';      // disable UTF-8
$texy->imageModule->root = 'images/';  // specify image folder
$texy->allowed['phraseIns'] = TRUE;
$texy->allowed['phraseDel'] = TRUE;
$texy->allowed['phraseSup'] = TRUE;
$texy->allowed['phraseSub'] = TRUE;
$texy->allowed['phraseCite'] = TRUE;
$texy->allowed['script'] = TRUE;
$texy->allowed['htmlComment'] = TRUE;


// processing
$text = file_get_contents('syntax.texy');
$html = $texy->process($text);  // that's all folks!


// echo formated output
header('Content-type: text/html; charset=' . $texy->encoding);
echo '<link rel="stylesheet" type="text/css" media="all" href="style.css" />';
echo '<title>' . $texy->headingModule->title . '</title>';
echo $html;


// and echo generated HTML code
echo '<hr />';
echo '<pre>';
echo htmlSpecialChars($html);
echo '</pre>';

?>