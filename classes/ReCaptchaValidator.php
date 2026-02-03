<?php
namespace JosephCrowell\MagicForms\Classes;

use Illuminate\Support\Facades\Request;
use JosephCrowell\MagicForms\Models\Settings;

class ReCaptchaValidator
{
    public function validateReCaptcha($attribute, $value, $parameters)
    {
        $version = Settings::get('recaptcha_version') ?: 'v2';
        $secret_key = Settings::get('recaptcha_secret_key');
        $recaptcha = post('g-recaptcha-response');
        $ip = Request::getClientIp();

        $data = [
            'secret' => $secret_key,
            'response' => $recaptcha
        ];

        // IP is optional and sometimes problematic with proxies, maybe omit or keep?
        // v3 documentation says remoteip is optional. v2 also.
        // Let's keep it if it was there, but maybe use curl or post for better reliability?
        // existing code used get with query params.

        if ($version == 'v3') {
            $URL = "https://www.google.com/recaptcha/api/siteverify";
            // file_get_contents with query params is simple.
            $query = http_build_query([
                'secret' => $secret_key,
                'response' => $recaptcha,
                'remoteip' => $ip
            ]);
            $response = json_decode(file_get_contents($URL . '?' . $query), true);

            $threshold = Settings::get('recaptcha_score_threshold') ?: 0.5;

            return ($response['success'] == true && $response['score'] >= $threshold);
        }

        $URL = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha&remoteip=$ip";
        $response = json_decode(file_get_contents($URL), true);
        return ($response['success'] == true);
    }
}
