<?php 
namespace Annisa\Form;

use Illuminate\Support\ServiceProvider;
use Collective\Html\FormFacade; 

class AnnisaServiceProvider extends ServiceProvider
{ 
	/**
	 * Default components.
	 * 
	 * @var array
	 */
	protected $bsComponents = [
		'inputTypes' => [
			'text', 
			'submit', 
			'number', 
			'email', 
			'date', 
			'button', 
			'textarea', 
			'checkbox', 
			'radio', 
			'file', 
			'password'
		],
		'selectables' => [
			'select', 
			'selectRange', 
			'selectMonth',
		]
	];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'annisa');
		$this->mergeConfigFrom(__DIR__.'/../config/annisa.php', 'annisa');
        $this->registerBootstrapComponents();  

	    $this->publishes([
	        __DIR__.'/../resources/views' => resource_path('views/vendor/annisa'),
	    ], 'annisa.form');

	    $this->publishes([
	        __DIR__.'/../config/annisa' => config_path(),
	    ], 'annisa.form');
 
	    if ($this->app->runningInConsole()) {
	        $this->commands([
	            Console\FormMakeCommand::class, 
	        ]);
	    }
    } 

    public function registerBootstrapComponents()
    {  
    	$this->registerInputs();
    	$this->registerSelects();
    }

    public function registerInputs()
    { 

    	foreach ($this->bsComponents['inputTypes'] as $type) { 

    		$signatures = ['name', 'label' => null];

    		if (! in_array($type, ['file', 'password'])) {
    			$signatures['value'] = null;
    		}  
    		if (in_array($type, ['checkbox', 'radio'])) {
    			$signatures['selected'] = [];
    		}  

    		$signatures['attributes'] = [];
    		$signatures['label_attributes'] = [];
    		$signatures['wrapper_attributes'] = [];
    		$signatures['type'] = $type;  
    		 
	    	FormFacade::component('bs' .ucfirst($type), 'annisa::components.input', $signatures);
    	}     
    }

    public function registerSelects()
    { 
    	foreach ($this->bsComponents['selectables'] as $type) {   
    		$signatures = [
	    		'name',
	    		'label' 	=> null,
	    	];

	    	if($type == 'selectRange') { 
	    		$signatures['begin']= 0;
	    		$signatures['end']  = 0;
	    	} else if($type != 'selectMonth') { 
	    		$signatures['values'] = [];
	    	}

	    	$signatures['selected'] = [];
	    	$signatures['attributes'] = [];

	    	if($type == 'select') {
	    		$signatures['options_attributes'] = []; 
	    		$signatures['optiongroups_attributes'] = []; 
	    	}

	    	$signatures['label_attributes'] = [];
	    	$signatures['wrapper_attributes'] = [];

	    	if($type == 'selectMonth') {
	    		$signatures['format'] = '%B';
	    	}

	    	$signatures['type'] = $type;  

	    	FormFacade::component('bs' .ucfirst($type), 'annisa::components.select', $signatures);
    	}  

    	FormFacade::macro('bsSelectYear', function () {
    		return call_user_func_array([$this, 'bsSelectRange'], func_get_args()); 
    	});
    } 
}