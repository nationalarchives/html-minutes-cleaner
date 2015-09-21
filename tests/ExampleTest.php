<?php

/**
 * Created by PhpStorm.
 * User: gwynjones
 * Date: 19/09/15
 * Time: 17:38
 */

require dirname(__DIR__) . '/src/Example.php';

class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSetsName()
    {
        $acme = new Example('Road Runner');
        $this->assertAttributeEquals('Road Runner', 'name', $acme, "Something wen't wrong, chaps!");

    }

}
