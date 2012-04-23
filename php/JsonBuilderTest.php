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

        $this->assertEqual("$null", 'null');
        $this->assertEqual("$int", '123');
        $this->assertEqual("$str", '"foo"');
    }

    public function testOneDimension() {
        $json = $this->builder;
        $json->foo = 'bar';

        $expect = array('foo' => 'bar');
        $this->assertEqual("$json", json_encode($expect));
    }

}

?>
