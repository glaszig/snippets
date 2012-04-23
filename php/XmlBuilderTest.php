<?php

require_once 'simpletest/autorun.php';
require_once 'XmlBuilder.php';

class XmlBuilderTest extends UnitTestCase {

    public $builder;

    public function setUp() {
        $this->builder = new XmlBuilder;
        $this->builder->element()->formatOutput = false;
    }

    public function testSimpleDocument() {
        $xml = $this->builder;
        $xml->foo = 'bar';
        $this->expectXml("<foo>bar</foo>");
    }

    public function testOneChild() {
        $xml = $this->builder;
        $xml->foo->bar = 'baz';
        $this->expectXml("<foo><bar>baz</bar></foo>");
    }

    public function testMultipleChildren() {
        $xml = $this->builder;
        $xml->foo->bar = 'baz';
        $xml->foo->john = 'doe';
        $this->expectXml("<foo><bar>baz</bar><john>doe</john></foo>");
    }

    public function testSingleAttribute() {
        $xml = $this->builder;
        $xml->foo['bar']= 'baz';
        $this->expectXml('<foo bar="baz"/>');
    }

    public function testMultipleAttributes() {
        $xml = $this->builder;
        $xml->foo['bar']= 'baz';
        $xml->foo['john']= 'doe';
        $this->expectXml('<foo bar="baz" john="doe"/>');
    }

    public function testAttributeInChild() {
        $xml = $this->builder;
        $xml->foo->bar['john']= 'doe';
        $this->expectXml('<foo><bar john="doe"/></foo>');
    }

    public function testAttributeAndChildWithSameName() {
        $xml = $this->builder;
        $xml->foo['bar'] = 'baz';
        $xml->foo->bar = 'baz';
        $this->expectXml('<foo bar="baz"><bar>baz</bar></foo>');
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
