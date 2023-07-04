<?php

namespace JosephCrowell\MagicForms\Components;

use JosephCrowell\MagicForms\Classes\MagicForm;

class GenericForm extends MagicForm
{
    public function componentDetails()
    {
        return [
            'name'        => 'josephcrowell.magicforms::lang.components.generic_form.name',
            'description' => 'josephcrowell.magicforms::lang.components.generic_form.description',
        ];
    }
}
