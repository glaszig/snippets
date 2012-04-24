<?php

class JsonBuilder implements ArrayAccess {

    protected $struct = array();

    public function __construct() {
        if (func_num_args() > 0) {
            $arg = func_get_arg(0);
            $this->struct = $arg;
        }
    }

    public function __set($key, $value) {
        $this->struct[$key] = $value;
    }

    public function __get($key) {
        return $this->child($key);
    }

    public function __unset($key) {
        if (isset($this->struct[$key])) {
            unset($this->struct[$key]);
        }
    }

    public function offsetExists($offset) {
        return isset($this->struct[$offset]);
    }

    public function offsetGet($offset) {
        return $this->struct[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->struct[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->struct[$offset]);
    }

    protected function child($name) {
        if (!isset($this->struct[$name])) {
            $this->struct[$name] = new self();
        }
        return $this->struct[$name];
    }

    public function toJson() {
        $struct = null;
        $this->build($struct);
        return json_encode($struct);
    }

    public function build(&$struct) {
        if (!is_array($this->struct)) {
            $struct = $this->struct;
            return;
        }
        foreach ($this->struct as $key => $value) {
            if ($value instanceof self)
                $value->build($struct[$key]);
            else
                $struct[$key] = $value;
        }
    }

    public function __toString() {
        return $this->toJson();
    }

}

?>
