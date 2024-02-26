<?php
namespace JosephCrowell\MagicForms\Components;

use JosephCrowell\MagicForms\Classes\MagicForm;

class EmptyForm extends MagicForm
{
    public function componentDetails()
    {
        return [
            'name'        => 'josephcrowell.magicforms::lang.components.empty_form.name',
            'description' => 'josephcrowell.magicforms::lang.components.empty_form.description',
        ];
    }
}
