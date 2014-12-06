<?php
abstract class formula
{
	private $params;

	abstract public function eval_params($values);
}

class formula_const extends formula
{
	private $value;

	public function eval_params($values)
	{
		return $this->value;
	}
}

class formula_level extends formula
{
	private $id;

	public function eval_params($values)
	{
		if(array_key_exists($id, $values))
			return $values[$id];
		else
			return null;
	}
}

class formula_sum extends formula
{
	private $terms;

	public function eval_params($values)
	{
		return array_sum(array_map(function ($x) use($values) { return $x->eval_params($values); }, $terms));
	}
}

class formula_product extends formula
{
	private $terms;

	public function eval_params($values)
	{
		return array_product(array_map(function ($x) use($values) { return $x->eval_params($values); }, $terms));
	}
}
?>
