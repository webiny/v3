$this->attr('{$attribute.name}')->decimal(){if isset($attribute.defaultValue)}->defaultValue({$attribute.defaultValue}){/if}{if isset($attribute.required) && $attribute.required == true}->required(true){/if};