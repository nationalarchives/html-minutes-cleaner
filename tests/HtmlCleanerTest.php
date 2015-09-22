<?php

/**
 * Created by PhpStorm.
 * User: gwynjones
 * Date: 22/09/15
 * Time: 09:49
 */

require dirname(__DIR__) . '/src/HtmlCleaner.php';

class HtmlCleanerTest extends PHPUnit_Framework_TestCase
{
    protected $obj;

    protected function setUp()
    {
        $this->obj = new HtmlCleaner('word-filtered-output.html');
    }

    public function testConstructorRetrievesHtmlString()
    {
        $this->assertAttributeContains('<html>', 'originalFileContents', $this->obj);
    }

    public function testoriginalFileContentsCleanable()
    {
        $this->assertAttributeContains('<style>', 'originalFileContents', $this->obj);
        $this->assertAttributeContains('<head>', 'originalFileContents', $this->obj);
        $this->assertAttributeContains('<meta ', 'originalFileContents', $this->obj);
        $this->assertAttributeContains('<title>', 'originalFileContents', $this->obj);
        $this->assertAttributeContains('style=\'', 'originalFileContents', $this->obj);
    }

    public function testRemoveUnwantedTag()
    {
        $this->obj->removeUnwantedTags();

        $this->assertAttributeNotContains('<style>', 'processedFileContents', $this->obj);
        $this->assertAttributeNotContains('<script>', 'processedFileContents', $this->obj);
        $this->assertAttributeNotContains('<meta>', 'processedFileContents', $this->obj);
        $this->assertAttributeNotContains('<img>', 'processedFileContents', $this->obj);
    }

    public function testRemoveUnwantedAttributes()
    {
        $this->obj->removeUnwantedAttributes();
        $this->assertAttributeNotContains('style=', 'processedFileContents', $this->obj);
        $this->assertAttributeNotContains('height=', 'processedFileContents', $this->obj);
        $this->assertAttributeNotContains('width=', 'processedFileContents', $this->obj);
    }

    public function testRemoveUnwantedStrings()
    {
        $this->obj->removeUnwantedStrings();
        foreach ($this->obj->getUnwantedStrings() as $str) {
            $this->assertAttributeNotContains($str, 'processedFileContents', $this->obj);
        }

    }


}
