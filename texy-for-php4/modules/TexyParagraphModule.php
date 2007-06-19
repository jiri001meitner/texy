<?php

/**
 * This file is part of the Texy! formatter (http://texy.info/)
 *
 * Copyright (c) 2004-2007 David Grudl aka -dgx- (http://www.dgx.cz)
 *
 * @version  $Revision$ $Date$
 * @package  Texy
 */

// security - include texy.php, not this file
if (!class_exists('Texy')) die();



/**
 * Paragraph module
 */
class TexyParagraphModule extends TexyModule
{
    /** @var bool  how split paragraphs (internal usage) */
    var $mode;



    function begin()
    {
        $this->mode = TRUE;
    }



    /**
     * Finish invocation
     *
     * @param string
     * @param TexyModifier
     * @return TexyHtml|FALSE
     */
    function solve($content, $mod)
    {
        $tx = $this->texy;

        // find hard linebreaks
        if ($tx->mergeLines) {
            // ....
            //  ...  => \r means break line
            $content = preg_replace('#\n (?=\S)#', "\r", $content);
        } else {
            $content = preg_replace('#\n#', "\r", $content);
        }

        $el = TexyHtml::el('p');
        $el->parseLine($tx, $content);
        $content = $el->getText(); // string

        // check content type
        // block contains block tag
        if (strpos($content, TEXY_CONTENT_BLOCK) !== FALSE) {
            $el->setName(NULL);  // ignores modifier!

        // block contains text (protected)
        } elseif (strpos($content, TEXY_CONTENT_TEXTUAL) !== FALSE) {
            // leave element p

        // block contains text
        } elseif (preg_match('#[^\s'.TEXY_MARK.']#u', $content)) {
            // leave element p

        // block contains only replaced element
        } elseif (strpos($content, TEXY_CONTENT_REPLACED) !== FALSE) {
            $el->setName('div');

        // block contains only markup tags or spaces or nothig
        } else {
            if ($tx->ignoreEmptyStuff) return FALSE;
            if ($mod->empty) $el->setName(NULL);
        }

        if ($el->getName()) {
            // apply modifier
            if ($mod) $mod->decorate($tx, $el);

            // add <br />
            if (strpos($content, "\r") !== FALSE) {
                $key = $tx->protect('<br />', TEXY_CONTENT_REPLACED);
                $content = str_replace("\r", $key, $content);
            };
        }

        $content = strtr($content, "\r\n", '  ');
        $el->setText($content);

        return $el;
    }


} // TexyParagraphModule