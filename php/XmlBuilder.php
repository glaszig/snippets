<?php

/**
 * XML builder class
 * Inspired by Rails' XMLBuilder.
 *
 * Usage:
 * {{{
 *  $xml = new XmlBuilder;
 *  $xml->foo->bar->baz;
 *  $xml->foo->bla = 'blubb';
 *  $xml->foo->bla['title'] = 'empty';
 *  echo $xml;
 *  unset($xml->foo->bar);
 *  echo $xml;
 * }}}
 *
 * @author glaszig at gmail dot com
 */
class XmlBuilderElement implements ArrayAccess {
    
    protected $element = null;
    protected $attributes = array();
    protected $children = array();
    
    public function __construct($name) {
        $this->element = new DOMElement($name);
    }
    
    public function __get($name) {
        return $this->child($name);
    }
    
    public function __set($name, $value) {
        return $this->child($name)->value($value);
    }

    public function __isset($name) {
        return isset($this->children[$name]) && isset($this->children[$name][0]);
    }

    public function __unset($name) {
        unset($this->children[$name]);
        foreach($this->element->getElementsByTagName($name) as $node) {
            $this->element->removeChild($node);
        }
    }
    
    public function child($name, $index = 0) {
        if (!isset($this->children[$name][$index]))
            $this->children[$name][$index] = new XmlBuilderElement($name);
        return $this->children[$name][$index];
    }
    
    protected function value() {
        if (func_num_args() == 1) {
            $this->element->nodeValue = func_get_arg(0);
            return $this;
        }
        return $this->element->nodeValue;
    }
    
    public function offsetExists($offset) {
        return isset($this->attributes[$offset]);
    }
    
    public function offsetGet($offset) {
        return $this->attributes[$offset];
    }
        
    public function offsetSet($offset, $value) {
        $this->attributes[$offset] = $value;
    }
        
    public function offsetUnset($offset) {
        unset($this->attributes[$offset]);
    }
    
    public function &element() {
        return $this->element;
    }
    
    public function toXml($version = '1.0', $encoding = 'UTF-8') {
        $dom = new DOMDocument($version, $encoding);
        $rootNode = $dom->appendChild($this->element);
        $this->build($rootNode);
        return $dom->saveXML();
    }
    
    protected function build($dom = null) {
        foreach ($this->children as $name => $children) {
            foreach($children as $child) {
                $node = $dom->appendChild($child->element());
                $child->build($node);
            }
        }
        foreach ($this->attributes as $name => $value) {
            $this->element->setAttribute($name, $value);
        }
        return $dom;
    }
    
    public function __toString() {
        return $this->toXml();
    }
    
}

class XmlBuilder extends XmlBuilderElement {
    
    public function __construct($version = '1.0', $encoding = 'UTF-8') {
        $this->element = new DOMDocument($version, $encoding);
        $this->init();
    }
    
    protected function init() {
        $this->element->formatOutput = true;
    }
    
    public function instruct() {
        echo '<?xml version="'.$this->element->xmlVersion.'" encoding="'.$this->element->xmlEncoding.'" ?>';
    }
  
    public function toXml() {
        return $this->build($this->element)->saveXML();
    }
}

?>
