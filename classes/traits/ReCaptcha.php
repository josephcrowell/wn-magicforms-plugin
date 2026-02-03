<?php
namespace JosephCrowell\MagicForms\Classes\Traits;

use JosephCrowell\MagicForms\Classes\BackendHelpers;
use JosephCrowell\MagicForms\Models\Settings;
use Winter\Translate\Classes\Translator;

trait ReCaptcha
{
    /**
     * @var string The active locale code.
     */
    public $activeLocale;

    private function isReCaptchaEnabled()
    {
        return ($this->property('recaptcha_enabled') && Settings::get('recaptcha_site_key') != '' && Settings::get('recaptcha_secret_key') != '');
    }

    private function isReCaptchaMisconfigured()
    {
        return ($this->property('recaptcha_enabled') && (Settings::get('recaptcha_site_key') == '' || Settings::get('recaptcha_secret_key') == ''));
    }

    private function getReCaptchaLang($lang = '')
    {
        if (BackendHelpers::isTranslatePlugin()) {
            $lang = '&hl=' . $this->activeLocale = Translator::instance()->getLocale();
        } else {
            $lang = '&hl=' . $this->activeLocale = app()->getLocale();
        }
        return $lang;
    }

    private function loadReCaptcha()
    {
        $version = Settings::get('recaptcha_version') ?: 'v2';

        if ($version == 'v3') {
            $siteKey = Settings::get('recaptcha_site_key');
            $this->addJs('https://www.google.com/recaptcha/api.js?render=' . $siteKey . $this->getReCaptchaLang(), ['async', 'defer']);
            // No custom JS needed for generic load, but we might add a helper for token generation
            // Actually, we'll put the token generation logic in the component partial or a separate js file specific to the form
        } else {
            $this->addJs('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' . $this->getReCaptchaLang(), ['async', 'defer']);
            $this->addJs('assets/js/recaptcha.js');
        }
    }
}
