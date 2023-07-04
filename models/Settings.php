<?php

namespace JosephCrowell\MagicForms\Models;

use Winter\Storm\Database\Model;

class Settings extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $implement      = ['System.Behaviors.SettingsModel'];
    public $settingsCode   = 'josephcrowell_magicforms_settings';
    public $settingsFields = 'fields.yaml';

    public $rules = [
        'gdpr_days' => 'required|numeric',
    ];

    public $attributeNames = [
        'gdpr_days' => 'GDPR',
    ];
}
