<?php
namespace JosephCrowell\MagicForms\Components;

use JosephCrowell\MagicForms\Classes\MagicForm;

class FilePondForm extends MagicForm
{
    public function componentDetails()
    {
        return [
            'name' => 'josephcrowell.magicforms::lang.components.filepond_form.name',
            'description' => 'josephcrowell.magicforms::lang.components.filepond_form.description',
        ];
    }

    public function defineProperties()
    {
        $local = [
            'mail_uploads' => [
                'title' => 'josephcrowell.magicforms::lang.components.shared.mail_uploads.title',
                'description' => 'josephcrowell.magicforms::lang.components.shared.mail_uploads.description',
                'type' => 'checkbox',
                'default' => false,
                'group' => 'josephcrowell.magicforms::lang.components.shared.group_mail',
                'showExternalParam' => false,
            ],
            'uploader_enable' => [
                'title' => 'josephcrowell.magicforms::lang.components.shared.uploader_enable.title',
                'description' => 'josephcrowell.magicforms::lang.components.shared.uploader_enable.description',
                'default' => false,
                'type' => 'checkbox',
                'group' => 'josephcrowell.magicforms::lang.components.shared.group_uploader',
                'showExternalParam' => false,
            ],
        ];

        return array_merge(parent::defineProperties(), $local);
    }
}
