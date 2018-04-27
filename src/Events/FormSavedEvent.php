<?php
namespace Annisa\Form\Events;

use Annisa\Form\Contracts\Form; 

class FormSavedEvent
{ 
    public $form;

    private $name;

    public $saved;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Form $form, $saved = null)
    {
        $this->form = $form;

        $this->saved = $saved;

        $this->name = get_class($form);
    } 

    public function name()
    {
        return $this->name;
    }
}
