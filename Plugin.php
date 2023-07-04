<?php

namespace JosephCrowell\MagicForms;

use Backend\Facades\Backend;
use JosephCrowell\MagicForms\Classes\GDPR;
use System\Classes\PluginBase;
use JosephCrowell\MagicForms\Models\Settings;
use System\Classes\SettingsManager;
use Illuminate\Support\Facades\Lang;
use JosephCrowell\MagicForms\Classes\UnreadRecords;
use JosephCrowell\MagicForms\Classes\BackendHelpers;
use Winter\Storm\Support\Facades\Validator;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'josephcrowell.magicforms::lang.plugin.name',
            'description' => 'josephcrowell.magicforms::lang.plugin.description',
            'author'      => 'Joseph Crowell',
            'icon'        => 'icon-magic',
            'homepage'    => 'https://github.com/josephcrowell/wn-magicforms-plugin',
            'replaces' => [
                'Martin.MagicForms' => '<=2.0.0'
            ],
        ];
    }

    public function registerNavigation()
    {
        if (Settings::get('global_hide_button', false)) {
            return;
        }

        return [
            'forms' => [
                'label'       => 'josephcrowell.magicforms::lang.menu.label',
                'icon'        => 'icon-magic',
                'iconSvg'     => 'plugins/josephcrowell/magicforms/assets/imgs/icon.svg',
                'url'         => BackendHelpers::getBackendURL(['josephcrowell.magicforms.access_records' => 'josephcrowell/magicforms/records', 'josephcrowell.magicforms.access_exports' => 'josephcrowell/magicforms/exports'], 'josephcrowell.magicforms.access_records'),
                'permissions' => ['josephcrowell.magicforms.*'],
                'sideMenu' => [
                    'records' => [
                        'label'        => 'josephcrowell.magicforms::lang.menu.records.label',
                        'icon'         => 'icon-database',
                        'url'          => Backend::url('josephcrowell/magicforms/records'),
                        'permissions'  => ['josephcrowell.magicforms.access_records'],
                        'counter'      => UnreadRecords::getTotal(),
                        'counterLabel' => 'Un-Read Messages'
                    ],
                    'exports' => [
                        'label'       => 'josephcrowell.magicforms::lang.menu.exports.label',
                        'icon'        => 'icon-download',
                        'url'         => Backend::url('josephcrowell/magicforms/exports'),
                        'permissions' => ['josephcrowell.magicforms.access_exports']
                    ],
                ]
            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'josephcrowell.magicforms::lang.menu.label',
                'description' => 'josephcrowell.magicforms::lang.menu.settings',
                'category'    => SettingsManager::CATEGORY_CMS,
                'icon'        => 'icon-magic',
                'class'       => 'JosephCrowell\MagicForms\Models\Settings',
                'permissions' => ['josephcrowell.magicforms.access_settings'],
                'order'       => 500
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'josephcrowell.magicforms.access_settings' => ['tab' => 'josephcrowell.magicforms::lang.permissions.tab', 'label' => 'josephcrowell.magicforms::lang.permissions.access_settings'],
            'josephcrowell.magicforms.access_records'  => ['tab' => 'josephcrowell.magicforms::lang.permissions.tab', 'label' => 'josephcrowell.magicforms::lang.permissions.access_records'],
            'josephcrowell.magicforms.access_exports'  => ['tab' => 'josephcrowell.magicforms::lang.permissions.tab', 'label' => 'josephcrowell.magicforms::lang.permissions.access_exports'],
            'josephcrowell.magicforms.gdpr_cleanup'    => ['tab' => 'josephcrowell.magicforms::lang.permissions.tab', 'label' => 'josephcrowell.magicforms::lang.permissions.gdpr_cleanup'],
        ];
    }

    public function registerComponents()
    {
        return [
            'JosephCrowell\MagicForms\Components\GenericForm'  => 'genericForm',
            'JosephCrowell\MagicForms\Components\FilePondForm' => 'filepondForm',
            'JosephCrowell\MagicForms\Components\EmptyForm'    => 'emptyForm',
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'josephcrowell.magicforms::mail.notification' => Lang::get('josephcrowell.magicforms::lang.mails.form_notification.description'),
            'josephcrowell.magicforms::mail.autoresponse' => Lang::get('josephcrowell.magicforms::lang.mails.form_autoresponse.description'),
        ];
    }

    public function register()
    {
        $this->app->resolving('validator', function () {
            Validator::extend('recaptcha', 'JosephCrowell\MagicForms\Classes\ReCaptchaValidator@validateReCaptcha');
        });
    }

    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            GDPR::cleanRecords();
        })->daily();
    }
}
