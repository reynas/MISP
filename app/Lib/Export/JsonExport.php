<?php

class JsonExport
{
	private $__converter = false;
	public $non_restrictive_export = true;

    public function handler($data, $options = array())
    {
		if ($options['scope'] === 'Attribute') {
			return $this->__attributeHandler($data, $options);
		} else {
			return $this->__eventHandler($data, $options);
		}
    }

	private function __eventHandler($event, $options = array()) {
		if ($this->__converter === false) {
			App::uses('JSONConverterTool', 'Tools');
			$this->__converter = new JSONConverterTool();
		}
		return json_encode($this->__converter->convert($event, false, true));
	}

	private function __attributeHandler($attribute, $options = array())
	{
		$attribute = array_merge($attribute['Attribute'], $attribute);
		unset($attribute['Attribute']);
		if (isset($attribute['Object']) && empty($attribute['Object']['id'])) {
			unset($attribute['Object']);
		}
		if (isset($attribute['AttributeTag'])) {
			$attributeTags = array();
			foreach ($attribute['AttributeTag'] as $tk => $tag) {
				$attribute['Tag'][$tk] = $attribute['AttributeTag'][$tk]['Tag'];
			}
			unset($attribute['AttributeTag']);
			unset($attribute['value1']);
			unset($attribute['value2']);
		}
		return json_encode($attribute);
	}

    public function header($options = array())
    {
		if ($options['scope'] === 'Attribute') {
			return '{"response": {"Attribute": [';
		} else {
			return '{"response": [';
		}
    }

    public function footer($options = array())
    {
		if ($options['scope'] === 'Attribute') {
			return ']}}' . PHP_EOL;
		} else {
			return ']}' . PHP_EOL;
		}

    }

    public function separator()
    {
        return ',';
    }

}
