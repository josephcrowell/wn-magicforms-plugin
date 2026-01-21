<?php
namespace JosephCrowell\MagicForms\Models;

use Winter\Storm\Database\Model;

class Settings extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'josephcrowell_magicforms_settings';
    public $settingsFields = 'fields.yaml';

    public $rules = [
        'gdpr_days' => 'required|numeric',
        'global_allowed_filesize' => 'required|integer|min:0',
    ];

    public $attributeNames = [
        'gdpr_days' => 'GDPR',
    ];

    public function initSettingsData()
    {
        $kb = fn($value) => match (strtolower(substr(trim($value), -1))) {
            'g' => (float)$value * 1048576,  // 1024^2
            'm' => (float)$value * 1024,     // 1024^1
            'k' => (float)$value,
            default => (float)$value / 1024
        };

        $this->global_allowed_filesize = round(min(
            $kb(ini_get('upload_max_filesize')),
            $kb(ini_get('post_max_size'))
        ));
    }

}
