$this->attr('{$attribute.name}')->many2one()->setEntity('{$attribute.entity}'){if isset($attribute.defaultValue)}->setDefaultValue('{$attribute.defaultValue}'){/if}{if isset($attribute.required) && $attribute.required == true}->setRequired(true){/if};