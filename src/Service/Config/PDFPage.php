<?php

namespace Hurycan\Service;

class PDFPage
{
    private $pageNumber;
    private $text;
    private $imageCount;
    private $characterCount;

    public function __construct($pageNumber, $text, $imageCount)
    {
        $this->pageNumber = $pageNumber;
        $this->text = $text;
        $this->imageCount = $imageCount;
        $this->characterCount = strlen($text);
    }

    // Visszaadja az oldalszámot
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    // Visszaadja a szöveget
    public function getText()
    {
        return $this->text;
    }

    // Visszaadja az oldalon lévő képek számát
    public function getImageCount()
    {
        return $this->imageCount;
    }

    // Visszaadja az oldalon lévő karakterek számát
    public function getCharacterCount()
    {
        return $this->characterCount;
    }
}
