<?php

require_once 'simpletest/autorun.php';
require_once 'JsonBuilder.php';

class JsonBuilderTest extends UnitTestCase {

    public $builder;

    public function setUp() {
        $this->builder = new JsonBuilder;
    }

    public function testSingleValue() {
        $null = new JsonBuilder(null);
        $int = new JsonBuilder(123);
        $str = new JsonBuilder('foo');
        $obj = new JsonBuilder(array('key' => 'value'));
        $ary = new JsonBuilder(array('str', null, 123));

        $this->assertEqual("$null", 'null');
        $this->assertEqual("$int", '123');
        $this->assertEqual("$str", '"foo"');
        $this->assertEqual("$obj", '{"key":"value"}');
        $this->assertEqual("$ary", '["str",null,123]');
    }

    public function testOneDimension() {
        $json = $this->builder;
        $json->foo = 'bar';

        $expect = array('foo' => 'bar');
        $this->assertEqual("$json", json_encode($expect));
    }

    public function testMultiDimension() {
        $json = $this->builder;
        $json->foo->bar = "baz";

        $expect = array('foo' => array(
            'bar' => 'baz'
        ));
        $expect = json_encode($expect);
        $this->assertEqual("$json", $expect);

        $json->foo->int = 123;
        $json->null = null;
        $json->ary = array('key' => 'val');

        $expect = array(
            'foo' => array(
                'bar' => 'baz',
                'int' => 123
            ),
            'null' => null,
            'ary' => array('key' => 'val')
        );
        $expect = json_encode($expect);
        $this->assertEqual("$json", $expect);
    }

    public function testArrayAccess() {
        $json = $this->builder;
        $json->foo = array(123, "str");

        $this->assertEqual($json->foo[0], 123);
        $this->assertEqual($json->foo[1], "str");
    }

    public function testUnset() {
        $json = $this->builder;
        $json->foo->bar = "baz";

        $expect = array('foo' => array(
            'bar' => 'baz'
        ));
        $expect = json_encode($expect);
        $this->assertEqual("$json", $expect);

        unset($json->foo->bar);
        $expect = array('foo' => null);
        $expect = json_encode($expect);
        $this->assertEqual("$json", $expect);
    }

}

?>
