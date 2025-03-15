<?php

namespace Hurycan\Service;

use Smalot\PdfParser\Parser;
use Smalot\PdfParser\PdfDocument;

class PDFReader
{
    private $pdf;

    public function __construct($filePath = null)
    {
        if ($filePath) {
            $this->setPdf($filePath);
        }
    }

    // Getter a PDF fájlhoz
    public function getPdf()
    {
        return $this->pdf;
    }

    // Setter a PDF fájlhoz
    public function setPdf($filePath)
    {
        $parser = new Parser();
        $this->pdf = $parser->parseFile($filePath);
    }

    // Visszaadja az oldalak számát
    public function getPageCount()
    {
        if ($this->pdf instanceof PdfDocument) {
            return $this->pdf->getPagesCount();
        }
        return 0;
    }

    // Visszaadja a PDF oldalakhoz tartozó PDFPage objektumokat
    public function getPages()
    {
        $pages = [];
        if ($this->pdf instanceof PdfDocument) {
            foreach ($this->pdf->getPages() as $index => $page) {
                $text = $page->getText();
                $imageCount = count($page->getImages());
                $pages[] = new PDFPage($index + 1, $text, $imageCount); // Új PDFPage példány
            }
        }
        return $pages;
    }

    // Visszaadja a karakterek számát minden oldalon
    public function getCharacterCounts()
    {
        $charCounts = [];
        $pages = $this->getPages();

        foreach ($pages as $page) {
            $charCounts[] = $page->getCharacterCount();
        }
        return $charCounts;
    }

    // Ellenőrzi, hogy van-e kép a PDF-ben
    public function hasImages()
    {
        $pages = $this->getPages();

        foreach ($pages as $page) {
            if ($page->getImageCount() > 0) {
                return true;
            }
        }
        return false;
    }
}
