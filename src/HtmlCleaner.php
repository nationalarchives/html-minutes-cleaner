<?php

/**
 * Created by PhpStorm.
 * User: gwynjones
 * Date: 22/09/15
 * Time: 09:48
 */
class HtmlCleaner
{
    private $originalFileContents;

    private $pathToOriginalFile;

    private $domDocument;

    private $domX;

    private $processedFileContents = '';

    private $unwantedTags = array('style', 'head', 'img', 'meta');

    private $unwantedStrings = array('&Acirc;', '&nbsp;', '&acirc;', '&euro;', '&ldquo;');

    private $unwantedAttributes = array(
        'style',
        'width',
        'height',
        'align',
        'http-equiv',
        'cellspacing',
        'cellpadding',
        'vlink',
        'link',
        'content',
        'valign',
        'border',
        'lang'
    );

    public function getUnwantedTags()
    {
        return $this->unwantedTags;
    }

    public function getOriginalFileContents()
    {
        return $this->originalFileContents;
    }

    public function getProcessedFileContents()
    {
        return $this->processedFileContents;
    }

    public function removeElementsByTagName($tagName, $document)
    {
        do {
            $nodeList = $document->getElementsByTagName($tagName);
            for ($nodeIdx = 0; $nodeIdx < $nodeList->length; $nodeIdx++) {

                $node = $nodeList->item($nodeIdx);

                $node->parentNode->removeChild($node);
            }
        } while ($nodeList->length);

    }

    public function removeUnwantedTags()
    {
        foreach ($this->unwantedTags as $tag) {
            $this->removeElementsByTagName($tag, $this->domDocument);
            $this->processedFileContents = $this->domDocument->saveHTML();
        }

    }

    public function removeUnwantedAttributes()
    {
        $this->domX = new DOMXPath($this->domDocument);
        $items = $this->domX->query("//*");

        foreach ($items as $item) {
            foreach ($this->unwantedAttributes as $attribute) {
                $item->removeAttribute($attribute);
            }

        }

        $this->processedFileContents = $this->domDocument->saveHTML();
    }

    public function removeEmptyNodes()
    {
        $this->domX = new DOMXPath($this->domDocument);

        foreach ($this->domX->query('//*[not(node())]') as $node) {
            $node->parentNode->removeChild($node);
        }
    }

    public function removeUnwantedStrings()
    {
        foreach ($this->unwantedStrings as $string) {
            $this->processedFileContents = str_replace($string, '', $this->processedFileContents);
        }

    }

    public function getUnwantedStrings()
    {
        return $this->unwantedStrings;
    }

    public function writeCleanedHTMLToOutputFile()
    {
        $file = "html-output/cleaned-output.html";
        $outputFile = fopen($file, "w") or die("Unable to open file!");
        $content = $this->processedFileContents;
        fwrite($outputFile, $content);
        fclose($outputFile);
        echo 'Cleaned file written to ' . $file;
    }


    public function clean()
    {
        $this->removeUnwantedTags();

        $this->removeUnwantedAttributes();

        $this->removeUnwantedStrings();

        $this->removeEmptyNodes();

        $this->writeCleanedHTMLToOutputFile();
    }

    public function __construct($originalFileName = false)
    {

        $this->pathToOriginalFile = __DIR__ . '/../html-input/';

        if ($content = file_get_contents($this->pathToOriginalFile . $originalFileName)) {
            $this->originalFileContents = $content;
        }

        $this->domDocument = new DOMDocument();

        $this->domDocument->loadHTML($this->originalFileContents);

    }


}