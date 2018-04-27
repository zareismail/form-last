<?php
namespace Annisa\Form\Events;

use Annisa\Form\Contracts\Form; 

class FormBuildingEvent
{
    
    public $form;
    
    private $name;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->name = get_class($form);
    } 

    public function name()
    {
        return $this->name;
    }
}
