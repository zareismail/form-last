<?php 

namespace Annisa\Form;

trait ArrayTransformer 
{
	private $original;
	private $transformed;

	public function arrayTransform(array $data)
	{
		$this->original = $this->transformed = collect($data);

		$this->original->each(function ($value, $key) {
			$this->setTransformed($key, $this->transform($key, $value));
		}); 

		return $this->getTransformed();
	}

	public function setOriginal($key, $value)
	{
		$this->original->put($key, $value);

		return $this;
	}

	public function getOriginal($key = null, $default = null)
	{
		return $key ? $this->original->get($key, $default) : $this->original;
	}

	public function setTransformed($key, $value)
	{
		$this->transformed->put($key, $value);

		return $this;
	}

	public function getTransformed($key = null, $default = null)
	{ 
		return $key ? $this->transformed->get($key, $default) : $this->transformed;
	}

	public function transform($key, $value = null)
	{ 
		return $this->hasTransformer($key) ? $this->callTransformer($key, $value) : $value;
	}

	public function hasTransformer($key)
	{
		return method_exists($this, $this->getTransformer($key));
	}

	protected function getTransformer($key)
	{
		return camel_case("transform_{$key}");
	}

	protected function callTransformer($key, $value)
	{
		return call_user_func_array([$this, $this->getTransformer($key)], [$value]);
	}
}