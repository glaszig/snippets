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

    public function testRepeatedChildren() {
        $xml = $this->builder;
        $xml->foo->doe[0] = 'john';
        $xml->foo->doe[1] = 'jane';

        $this->expectXml('<foo><doe>john</doe><doe>jane</doe></foo>');
    }

    public function testRepeatedChildrenDiscontinuity() {
        $xml = $this->builder;
        $xml->foo->doe[0] = 'john';
        $xml->foo->doe[2] = 'jane';

        $this->expectXml('<foo><doe>john</doe><doe>jane</doe></foo>');
    }

    public function testRepeatedChildrenWithAttributes() {
        $xml = $this->builder;

        $xml->foo->doe[0] = 'john';
        $xml->foo->doe[1] = 'jane';

        $xml->foo->doe[0]['bar'] = 'baz';
        $xml->foo->doe[1]['lorem'] = 'ipsum';

        $this->expectXml('<foo><doe bar="baz">john</doe><doe lorem="ipsum">jane</doe></foo>');
    }

    public function testUnsetOfRepeatedNode() {
        $xml = $this->builder;
        $xml->foo->doe[0] = 'john';
        $xml->foo->doe[1] = 'jane';

        $xml->toXml();

        unset($xml->foo->doe[1]);
        $this->expectXml("<foo><doe>john</doe></foo>");
    }

    public function testUnsetOfInvalidNodeDoesntThrow() {
        $xml = $this->builder;
        $xml->foo->doe[0] = 'john';
        $xml->foo->doe[1] = 'jane';

        $xml->toXml();

        unset($xml->foo->doe[2]);
        $this->expectXml('<foo><doe>john</doe><doe>jane</doe></foo>');
    }

    protected function expectXml($expected) {
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n$expected\n";
        $this->assertEqual("{$this->builder}", $expected);
    }
}

?>
