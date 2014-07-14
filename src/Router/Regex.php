<?php

namespace System\Router;

class Regex {

    public $params = array();
    public $pattern;
    public $target;
    private $_conditions;

    public function __construct($pattern, $target, $conditions = array()) {
        $this->pattern = $pattern;
        $this->target = $target;
        $this->_conditions = $conditions;
    }

    function replace($matches) {
        $key = str_replace(':', '', $matches[0]);
        if (array_key_exists($key, $this->_conditions)) {
            return '(' . $this->_conditions[$key] . ')';
        } else {
            return '([a-zA-Z0-9_\+\-%]+)';
        }
    }
    
    public function isMatch($uri) {
        $pNames = array();
        $pValues = array();
        $pattern = $this->pattern;

        preg_match_all('@:([\w]+)@', $pattern, $pNames, PREG_PATTERN_ORDER);
        $pNames = $pNames[0];
        
        $url_regex = preg_replace_callback('#:[\w]+#', 'self::replace', $pattern);
        
        if (preg_match('#^' . $url_regex . '$#', $uri, $pValues)) {
            array_shift($pValues);
            
            foreach ($pNames as $index => $value) {
                $this->params[substr($value, 1)] = $pValues[$index];
            }

            return true;
        }
        
        return false;
    }
    
    public function getUrl($parameters = array()) {
        $result = $this->pattern;
        
        foreach ($parameters as $key => $value) {
            $result = str_replace(':' . $key, $value, $result);
        }
        
        return $result;
    }

}