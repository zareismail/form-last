<?php

namespace Annisa\Form\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class FormMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Form';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {   
        return parent::buildClass($name.'Form'); 
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {  
        return __DIR__.'/../../stubs/form.stub';
    } 


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->rootPath().'/'.str_replace('\\', '/', $name).'Form.php';
    }

    public function rootPath()
    {
       return $this->option('path') ? base_path($this->option('path')) : $this->laravel['path'];
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\Forms";
    }


    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->option('namespace') ?? $this->laravel->getNamespace();
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['form', null, InputOption::VALUE_OPTIONAL, 'The form class.'],

            ['path', null, InputOption::VALUE_OPTIONAL, 'The form class path.'],

            ['namespace', null, InputOption::VALUE_OPTIONAL, 'The form class namespace.'],

            ['force', null, InputOption::VALUE_NONE, 'The form class namespace.'],
        ];
    }
}
