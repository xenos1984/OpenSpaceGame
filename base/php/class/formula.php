<?php
abstract class formula
{
	abstract public function parameters();
	abstract public function evaluate($values);
}

class formula_const extends formula
{
	private $value;

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

	public function parameters()
	{
		return array($this->id);
	}

	public function evaluate($values)
	{
		if(array_key_exists($this->id, $values))
			return $values[$this->id];
		else
			return null;
	}
}

class formula_sum extends formula
{
	private $terms;

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
