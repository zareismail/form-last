<?php 
namespace Annisa\Form; 
 
use Annisa\Form\Contracts\Form;
use Closure;

abstract class AnnisaBuilder implements Form
{
	use ArrayTransformer;

	/**
	 * Builded flag.
	 * 
	 * @var boolean
	 */
	private $builded = false; 

	/**
	 * Form name.
	 * 
	 * @var Object|null
	 */
	protected $name = 'my-form';  

	/**
	 * Form parent.
	 * 
	 * @var Object|null
	 */
	private $parent = null;  

	/**
	 * Form prefix.
	 * 
	 * @var string | null
	 */
	protected $prefix = null;

	/**
	 * Form model.
	 * 
	 * @var object | null
	 */
	protected $model = null;
	
	/**
	 * Builder callback.
	 * 
	 * @var Closure | null
	 */
	protected $builderCallback = null;   

	/**
	 * Form elements.
	 * 
	 * @var \Illuminate\Support\Collection
	 */
	protected $rows;

	/**
	 * Rendered rows.
	 * 
	 * @var \Illuminate\Support\Collection
	 */
	private $renderedRows; 

	/**
	 * Child Form's.
	 * 
	 * @var \Illuminate\Support\Collection
	 */
	protected $childs;  

	/**
	 * Base builder.
	 * 
	 * @var object
	 */
	protected $builder; 

	/**
	 * Form's component.
	 * 
	 * @var string
	 */
	protected $component = 'bs';

	/**
	 * Internal events.
	 * 
	 * @var Illuminate\Support\Collection
	 */
	protected $events;  


	public function __construct(Closure $builderCallback=null)
	{
		$this->builderCallback = $builderCallback;
		$this->component= \Config::get('annisa.form.default_component', 'bs');;
		$this->rows 	= collect([]);
		$this->childs 	= collect([]);
		$this->renderedRows	= collect([]);
		$this->events	= collect([
			'row.rendering'	=> [],
			'row.rendered'	=> [],
		]);

		$this->builder(app('form'));
	} 

	/**
	 * Alias of setName.
	 * 
	 * @param  string|null $name  
	 * @return Illuminate\Support\Collection
	 */
	public function name(string $name = null)
	{    
		return $this->setName($name);
	} 

	/**
	 * Appending name to form.
	 * 
	 * @param  string|null $name  
	 * @return Illuminate\Support\Collection
	 */
	public function setName(string $name = null)
	{   
		$this->name = $name; 

		return $this;
	} 

	/**
	 * Getting form name.
	 * 
	 * @param  void   
	 * @return Illuminate\Support\Collection
	 */
	public function getName()
	{  
		return $this->name;
	} 
/**
	 * Alias of setParent.
	 * 
	 * @param  Form\Builder $parent     
	 * @return Form\Builder
	 */
	public function parent(Form $parent = null)
	{   
		return $this->setParent($parent); 
	} 

	/**
	 * Appending parent form.
	 * 
	 * @param  Form\Builder $parent     
	 * @return Form\Builder
	 */
	public function setParent(Form $parent = null)
	{  
		$this->parent = $parent;

		return $this; 
	} 

	/**
	 * Getting parent form.
	 * 
	 * @param  void     
	 * @return Form\Builder
	 */
	public function getParent()
	{  
		return $this->parent;
	} 

	/**
	 * Alias of setPrefix.
	 *  
	 * @param  string  $prefix    
	 * @return string|Form\Builder
	 */
	public function prefix(string $prefix=null)
	{  
		return $this->setPrefix($prefix);
	} 

	/**
	 * Appending or Getting form prefix.
	 *  
	 * @param  string  $prefix    
	 * @return string|Form\Builder
	 */
	public function setPrefix(string $prefix=null)
	{ 
		$this->prefix = $prefix;

		return $this;
	}  

	/**
	 * Getting form prefix.
	 * 
	 * @param  void    
	 * @return string|Form\Builder
	 */
	public function getPrefix()
	{  
		return $this->prefix;
	} 

	/**
	 * Alias of setModel.
	 * 
	 * @param  string $model   
	 * @return string | object
	 */
	public function model($model = null)
	{  
		return $this->setModel($model);
	}

	/**
	 * Appending Form Model.
	 * 
	 * @param  string $model   
	 * @return string | object
	 */
	public function setModel($model = null)
	{  
		$this->model = $model;

		return $this;
	}

	/**
	 * Getting Form Model.
	 * 
	 * @param  void   
	 * @return string | object
	 */
	public function getModel()
	{
		return $this->model; 
	}

	/**
	 * Alias of setChild
	 * 
	 * @param  string  $name   
	 * @param  Closure|null $callback form build callback
	 * 
	 * @return Form\Builder
	 */
	public function child(string $name, Closure $callback)
	{     
		return $this->setChild($name, $callback); 
	} 

	/**
	 * Appending or Getting child form.
	 * 
	 * @param  string  $name   
	 * @param  Closure|null $callback form build callback
	 * 
	 * @return Form\Builder
	 */
	public function setChild(string $name, Closure $callback)
	{    
		$child = $this->toBase($callback)->name($name)->parent($this);

		$this->childs->put($name, $child);

		return $this; 
	} 

	/**
	 * Appending or Getting child form.
	 * 
	 * @param  string  $name     
	 * @return Form\Builder
	 */
	public function getChild(string $name)
	{     
		if($this->childs->has($name)) { 
			return $this->childs->get($name);
		}

		throw new NotExistsForm($name); 
	} 

	/**
	 * Appending or Getting child form.
	 * 
	 * @param  array  $names   
	 * @param  boolean
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function childs(array $names = [])
	{ 
		$filters = collect((array) $names)->flip();

		return $this->childs->filter(function($child) use ($filters) {   
			return $filters->count()? $filters->has($child->getName()) : true; 
		});
	} 

	/**
	 * New instance.
	 *   
	 * @param  Closure|null $callback form build callback
	 * 
	 * @return Form\Builder
	 */
	protected function toBase(Closure $callback=null)
	{  
		return new static($callback);
	}

	/**
	 * Is child ?
	 *     
	 * @return boolean
	 */
	public function isChild()
	{  
		return $this->parent instanceof Form;
	} 
	

	/**
	 * Saving callback.
	 * 
	 * @param  Closure  $prefix    
	 * @return mixed
	 */
	public function save(Closure $callback)
	{
		$this->runBuilder();

		event(new Events\FormSavingEvent($this));  

		return tap($callback($this->fetchData(), $this), function($result) { 
			event(new Events\FormSavedEvent($this, $result)); 
		});
	}

	/**
	 * Getting all the rows from the request and converting them. 
	 * 
	 * @return Illuminate\Support\Collection
	 */
	protected function fetchData()
	{
		$validInputs = collect([]); 

		foreach ($this->rows() as $row) {
			if($name = array_get($row, 'name')) { 
				$validInputs->put($name, $this->getInput($name)); 
			} 
		}   

		$this->arrayTransform($validInputs->toArray());

		return collect([
			'original'	=> $this->getOriginal(), 
			'transformed' => $this->getTransformed()
		]);
	}

	/**
	 * Getting input from request. 
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function getInput($key, $default = null)
	{
		return request()->input($key, $default);
	}

	/**
	 * Appending or Getting form Builder.
	 * 
	 * @param  Collective\Html\FormBuilder $builder
	 *  
	 * @return Collective\Html\FormBuilder | Form\Builder
	 */
	public function builder($builder = null)
	{
		if(! is_null($builder)) {
			$this->builder = $builder;

			return $this;
		}

		return $this->builder;
	} 

	/**
	 * Merging an row.
	 * 
	 * @param  string $rowName 
	 * @param  Closure $callback 
	 * @return Form\Builder
	 */
	public function merge($rowName, Closure $callback)
	{
		$this->rows = $this->rows->map(function($row) use ($rowName, $callback) { 
			return ($rowName == $row['name']) ? $callback($row) : $row;
		});

		return $this;
	} 

	/**
	 * Appending a form element.
	 * 
	 * @param  string $type 
	 * @param  string $name   
	 * @param  ...   
	 * @return Form\Builder
	 */
	public function element(string $type, string $name)
	{   
		$args = (array) array_except(func_get_args(), [0,1]);   

		$this->rows->put($this->appendPrefix($name), compact('type', 'name') + $args);

		return $this; 
	} 

	/**
	 * Appending a field.
	 * 
	 * @param  string $type 
	 * @param  string $name   
	 * @param  ...   
	 * @return Form\Builder
	 */
	public function field(string $type, string $name)
	{  
		$args = array_replace(func_get_args(), [$this->component.ucfirst($type)]);  

		return call_user_func_array([$this, 'element'], $args); 
	}   

	/**
	 * Rendering form rows.
	 * 
	 * @param  string | array $rowName  
	 * @param  boolean $force  
	 * @return string
	 */
	public function render($rows = [], $force=false) 
	{     
		$this->builder->setModel($this->getModel());  

		$this->runBuilder(); 
 	
	 	foreach ($this->rows((array) $rows, $force) as $row) {  

	 		$this->event('row.rendering', $row);

	 		echo $this->toHtml($row); 

	 		$this->event('row.rendered', $row); 

	 		$this->renderedRows($row['name']);
	 	} 

	 	$this->childs()->each(function ($child) use ($rows) { 
	 		return empty($rows)? $child->render() : false;
	 	});

		return ''; 
	}

	/**
	 * Builder call. 
	 * 
	 * @param  boolean $force [force to run form builder]
	 * @return void
	 */
	private function runBuilder($force=false)
	{
		if(! $this->isBuilded() || $force) {
			event(new Events\FormBuildingEvent($this)); 

			if(is_callable($this->builderCallback)) {
				// build by closure callback
				call_user_func($this->builderCallback, $this);
			} else {
				// build by class method
				$this->build(); 
			} 

			event(new Events\FormBuildedEvent($this));
		}  

		$this->builded = true;
	}   

	/**
	 * Is builded ?. 
	 *  
	 * @return boolean
	 */
	public function isBuilded()
	{
		return (boolean) $this->builded;
	}

	/**
	 * Your builder.
	 *   
	 * @return void
	 */
	abstract public function build(); 

	/**
	 * Builder call.  
	 * 
	 * @param  boolean $force 
	 * @return void
	 */
	public function doBuild($force=false)
	{
		$this->runBuilder($force);

		return $this; 
	}

	/**
	 * Retrieving renderables rows.
	 * 
	 * @param  string | array $name  
	 * @param  boolean $rendered  
	 * @return Illuminate\Support\Collection
	 */
	public function rows($name = [], $force=false)
	{
		$names = collect((array) $name)->flip();

		return $this->rows->filter(function($row) use ($names, $force) {   
			if($names->count() && !$names->has($row['name'])) {  
				return false; 
			}

			return $force || !$this->isRendered($row['name']); 
		});
	}

	/**
	 * Is row rendered?.
	 * 
	 * @param  string $rowName  
	 * @return boolean
	 */
	public function isRendered($rowName)
	{
		return $this->renderedRows->search($rowName) !== false;
	}

	/**
	 * Creating internal event.
	 * 
	 * @param	string $event  
	 * @param	array | null $row  
	 * @return void
	 */
	protected function event($event, $row = null)
	{  
		foreach ((array) $this->events->get($event) as $callback) {
			if(is_callable($callback)) {
				call_user_func_array($callback, [$this, $row]);
			}
		}  
	}  

	/**
	 * Watching internal event.
	 * 
	 * @param	string $event  
	 * @param	Closure $callback  
	 * @return void
	 */
	public function pushEvent($event, Closure $callback)
	{     
		$this->events->put(
			$event, 
			array_merge($this->events->get($event, []), [$callback])
		);

		return $this; 
	} 

	/**
	 * Converting row to html.
	 * 
	 * @param	array $row   
	 * @return Collective\Html\FormBuilder
	 */
	public function toHtml($row)
	{     
		return call_user_func_array(
			[$this->builder, array_get($row, 'type', 'text')], 
			array_except($row, 'type')
		);
	}

	/**
	 * Prefixing.
	 *  
	 * @param  string $name     
	 * @return Form\Builder
	 */
	protected function appendPrefix(string $name)
	{  
		$trimmed = rtrim($name, ']');

		return isset($this->prefix)? "{$this->prefix}[$trimmed]" : $name;
	}

	/**
	 * Pushing or Retrieving rendered row.
	 * 
	 * @param array $rowName   
	 * @return Illuminate\Support\Collection | Form\Builder
	 */
	public function renderedRows($rowName = [])
	{
		if(empty($rowName)) {
			return $this->renderedRows;
		}

		$rendered = is_array($rowName) ? $rowName : func_get_args();

	 	$this->renderedRows = $this->renderedRows->merge($rendered);

	 	return $this;
	}

	function __toString()
	{
		return $this->render();
	}

	function __call($method, $params)
	{
		return call_user_func_array([$this->builder, $method], (array) $params);
	}
}
