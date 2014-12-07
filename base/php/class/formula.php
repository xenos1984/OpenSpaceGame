<?php
abstract class formula
{
	abstract public function parameters();
	abstract public function evaluate($values);

	public static function fromxml($xml)
	{
		$sfs = array();
		foreach($xml->childNodes as $cn)
		{
			if(!($cn->nodeType == XML_ELEMENT_NODE))
				continue;
			if($sf = self::fromxml($cn))
				$sfs[] = $sf;
			else
				return false;
		}

		switch($xml->localName)
		{
		case 'constant':
			return new formula_constant((float)($xml->getAttribute('value')));
		case 'level':
			return new formula_level($xml->getAttribute('id'));
		case 'sum':
			return new formula_sum($sfs);
		case 'product':
			return new formula_product($sfs);
		case 'power':
			return new formula_power($sfs[0], $sfs[1]);
		case 'exp':
			return new formula_exp($sfs[0]);
		case 'log':
			return new formula_log($sfs[0]);
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

class formula_power extends formula
{
	private $base, $exponent;

	public function __construct($x, $y)
	{
		$this->base = $x;
		$this->exponent = $y;
	}

	public function parameters()
	{
		return array_unique(array_merge($this->base->parameters(), $this->exponent->parameters()));
	}

	public function evaluate($values)
	{
		return pow($this->base->evaluate($values), $this->exponent->evaluate($values));
	}
}

class formula_exp extends formula
{
	private $arg;

	public function __construct($x)
	{
		$this->arg = $x;
	}

	public function parameters()
	{
		return $this->arg->parameters();
	}

	public function evaluate($values)
	{
		return exp($this->arg->evaluate($values));
	}
}

class formula_log extends formula
{
	private $arg;

	public function __construct($x)
	{
		$this->arg = $x;
	}

	public function parameters()
	{
		return $this->arg->parameters();
	}

	public function evaluate($values)
	{
		return log($this->arg->evaluate($values));
	}
}
?>
