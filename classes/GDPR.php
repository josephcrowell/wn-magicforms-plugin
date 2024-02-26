<?php
namespace JosephCrowell\MagicForms\Classes;

use Carbon\Carbon;
use JosephCrowell\MagicForms\Models\Record;
use JosephCrowell\MagicForms\Models\Settings;
use Winter\Storm\Support\Facades\Flash;

class GDPR
{
    public static function cleanRecords()
    {
        $gdpr_enable = Settings::get('gdpr_enable', false);
        $gdpr_days   = Settings::get('gdpr_days', false);

        if (!$gdpr_enable)
        {
            Flash::error(e(trans('josephcrowell.magicforms::lang.classes.GDPR.alert_gdpr_disabled')));
            return;
        }

        if ($gdpr_enable && is_numeric($gdpr_days))
        {
            $days = Carbon::now()->subDays($gdpr_days);
            $rows = Record::whereDate('created_at', '<', $days)->forceDelete();
            return $rows;
        }

        Flash::error(e(trans('josephcrowell.magicforms::lang.classes.GDPR.alert_invalid_gdpr')));
    }
}
