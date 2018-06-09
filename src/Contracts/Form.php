<?php 
namespace Annisa\Form\Contracts;

use Closure;

interface Form 
{ 
	/**
	 * Appending a form element.
	 * 
	 * @param  string $type 
	 * @param  string $name   
	 * @param  ...   
	 * @return Form\Builder
	 */
	public function element(string $type, string $name);

	/**
	 * Appending a field.
	 * 
	 * @param  string $type 
	 * @param  string $name   
	 * @param  ...   
	 * @return Form\Builder
	 */
	public function field(string $type, string $name);

	/**
	 * Appending or Getting child form.
	 * 
	 * @param  string  $prefix   
	 * @param  Closure $callback form build callback
	 * 
	 * @return Form\Builder
	 */
	public function child(string $prefix, Closure $callback); 
}