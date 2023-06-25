<?php

namespace tiagocomti\cryptbox\models\api\responses;

class BaseResponses
{
    public function __construct($values, $namespace = __NAMESPACE__)
    {
        if(!is_array($values)){
            $values = json_decode($values,true);
        }
        if(is_countable($values) && count($values) > 0){
            foreach ($values as $chave => $valor){
                if(property_exists(get_called_class(), $chave)){
                    if(is_array($valor)) {
                        $classname = $namespace . "\\" . ucfirst($chave);
                        if (class_exists($classname)) {
                            if(array_key_exists(0,$valor)){
                                $this->$chave = $valor;
                            }else{
                                $this->$chave = new $classname($valor);
                            }
                        }
                    }else{
                        $this->$chave = $valor;
                    }
                }
            }
        }
        return $this;
    }

}