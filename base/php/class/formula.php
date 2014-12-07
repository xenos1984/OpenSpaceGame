<?php
abstract class formula
{
	abstract public function parameters();
	abstract public function evaluate($values);

	public static function fromxml($xml)
	{
		switch($xml->localName)
		{
		case 'constant':
			return new formula_constant($xml->getAttribute('value'));
		case 'level':
			return new formula_level($xml->getAttribute('id'));
		case 'sum':
			$terms = array();
			foreach($xml->childNodes as $cn)
			{
				if($sf = self::fromxml($cn))
					$terms[] = $sf;
				else
					return false;
			}
			return new formula_sum($terms);
		case 'product':
			$terms = array();
			foreach($xml->childNodes as $cn)
			{
				if($sf = self::fromxml($cn))
					$terms[] = $sf;
				else
					return false;
			}
			return new formula_sum($terms);
		default:
			return false;
		}
	}
}

class formula_constant extends formula
{
	private $value;

	public function __construct($x)
	{
		$this->value = $x;
	}

	public function parameters()
	{
		return array();
	}

	public function evaluate($values)
	{
		return $this->value;
	}
}

class formula_level extends formula
{
	private $id;

	public function __construct($x)
	{
		$this->id = $x;
	}

	public function parameters()
	{
		return array($this->id);
	}

	public function evaluate($values)
	{
		if(array_key_exists($this->id, $values))
			return $values[$this->id];
		else
			return 0;
	}
}

class formula_sum extends formula
{
	private $terms;

	public function __construct($x)
	{
		$this->terms = $x;
	}

	public function parameters()
	{
		return array_unique(call_user_func_array("array_merge", array_map(function ($x) { return $x->parameters(); }, $this->terms)));
	}

	public function evaluate($values)
	{
		return array_sum(array_map(function ($x) use($values) { return $x->evaluate($values); }, $this->terms));
	}
}

class formula_product extends formula
{
	private $terms;

	public function __construct($x)
	{
		$this->terms = $x;
	}

	public function parameters()
	{
		return array_unique(call_user_func_array("array_merge", array_map(function ($x) { return $x->parameters(); }, $this->terms)));
	}

	public function evaluate($values)
	{
		return array_product(array_map(function ($x) use($values) { return $x->evaluate($values); }, $this->terms));
	}
}
?>
