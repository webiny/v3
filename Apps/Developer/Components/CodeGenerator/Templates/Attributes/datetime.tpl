$this->attr('{$attribute.name}')->datetime()->setFormat('{$attribute.format|default:'datetime'}'){if isset($attribute.defaultValue)}->setDefaultValue('{$attribute.defaultValue}'){/if}{if isset($required) && $required == true}->setRequired(true){/if};