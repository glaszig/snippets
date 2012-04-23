<?php

class JsonBuilder {

    protected $parent;
    protected $struct;

    public function __construct() {
        if (func_num_args() > 0) {
            $arg = func_get_arg(0);
            if ($arg instanceof self) {
                $this->parent = $arg;
            } else {
                $this->struct = $arg;
            }
        }
    }

    public function __set($key, $value) {
        $this->struct[$key] = $value;
    }

    public function __toString() {
        return json_encode($this->struct);
    }

}

?>