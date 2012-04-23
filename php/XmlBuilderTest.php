<?php

require_once 'simpletest/autorun.php';
require_once 'XmlBuilder.php';

class XmlBuilderTest extends UnitTestCase {
    
    public $builder;
    
    public function setUp() {
        $this->builder = new XmlBuilder;
        $this->builder->element()->formatOutput = false;
    }
    
    public function testUnset() {
        $xml = $this->builder;
        $xml->foo->bar = 'baz';
        unset($xml->foo->bar);

        $this->expectXml("<foo/>");
    }

    public function testUnsetAfterEcho() {
        $xml = $this->builder;
        $xml->foo->bar = 'baz';

        $xml->toXml();

        unset($xml->foo->bar);
        $this->expectXml("<foo/>");
    }

    protected function expectXml($expected) {
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n$expected\n";
        $this->assertEqual("{$this->builder}", $expected);
    }

}

?>
