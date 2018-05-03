<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author kek91, contributor OJ Perez <oj@ojperez.com> 
 * @link https://github.com/kek91/PHP-Input-Validation
 */


class Validate
{
    private $_passed = false,
            $_errors = array(),
            $_labels = array();
    
    public function check($source, $items = array(), $labels=array())
    {
        $this->_labels = $labels;
        foreach($items as $item => $rules)
        {
            $value = htmlspecialchars(trim($source[$item]), ENT_QUOTES, 'UTF-8');
            $label=isset($rules['label'])?$rules['label']:ucfirst($item);
            foreach($rules as $rule => $rule_value)
            {
                if ($rule=='label')
                    continue;
                switch($rule)
                {
                    case 'required':
                        if(empty($value)) {
                            $this->add_error("<b>".$label."</b> is required.");
                        }
                        break;
                    case 'length_min': //Takes empty string as valid -- Toma un string vacio como valido
                        if(strlen($value) > 0 && strlen($value) < $rule_value) {
                            $this->add_error("<b>".$label."</b> must contain minimum <b>{$rule_value}</b> characters");
                        }
                        break;
                    case 'length_max':
                        if(strlen($value) > $rule_value) {
                            $this->add_error("<b>".$label."</b> can't contain more than <b>{$rule_value}</b> characters");
                        }
                        break;
                    case 'matches':
                        if($value != htmlspecialchars(trim($source[$rule_value]), ENT_QUOTES, 'UTF-8')) {
                            $this->add_error("<b>".$label."</b> must match <b>{$rule_value}</b>");
                        }
                        break;
                    case 'mailcheck':
                        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->add_error("<b>{$value}</b> is not a valid email address");
                        }
                        break;
                    case 'numeric':
                        if(!ctype_digit(str_replace('+', '', $value))) {
                            $this->add_error("<b>".$label."</b>contains illegal characters. Only numbers 0-9 and \"+\"-sign are allowed");
                        }
                        break;
                    case 'alphabetic':
                        if (!ctype_alpha(str_replace(array(' ', "'", '-'), '', $value))) {
                            $this->add_error("<b>".$label."</b> contains illegal characters. Only alphabetic letters A-Z, \"'\", \" \" and \"-\" are allowed");
                        }
                        break;
                    case 'alphanumeric':
                        if(!ctype_alnum($value)) {
                            $this->add_error("<b>".$label."</b> contains illegal characters. Only alphabetic and numeric characters (A-Z and 0-9) are allowed");
                        }
                        break;
                    case 'blacklist':
                        foreach($rule_value as $blocked_word) {
                            if($value == $blocked_word) {
                                $this->add_error("<b>{$value}</b> is blocked");
                            }
                        }
                        break;
                    case 'whitelist':
                        foreach($rule_value as $approved_word) {
                            if($value == $approved_word) {
                                $match = true;
                                break;
                            }
                        }
                        if(!$match) {
                            $this->add_error("<b>{$value}</b> is blocked");
                        }
                }
            }
        }
        if(empty($this->_errors))
        {
            $this->_passed = true;
        }
        return $this;
    }

    private function add_error($error)
    {
        $this->_errors[] = $error;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }
}