$this->attr('{$attribute.name}')->char(){if isset($attribute.defaultValue)}->setDefaultValue('{$attribute.defaultValue}'){/if}{if isset($attribute.required) && $attribute.required == true}->setRequired(true){/if};