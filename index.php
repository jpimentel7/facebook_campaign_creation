<?php

use Carbon\Carbon;
use FacebookAds\Api;
use FacebookAds\Http\Exception\RequestException;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;

require 'vendor/autoload.php';
require 'FacebookSettings.php';

/**
 * Class FacebookCampaignCreation
 */
class FacebookCampaignCreation
{

    public static function main()
    {
        $facebookSettings = new FacebookSettings();

        // The facebook api has to be initiated before use
        Api::init($facebookSettings->getAppId(), $facebookSettings->getAppSecret(), $facebookSettings->getUserToken());

        $campaign = new Campaign(null, $facebookSettings->getDefaultAdAccount());

        // The current date is set as the start date
        $startDate = Carbon::now()->format($facebookSettings->getFacebookDateFormat());

        // The end date is one week from now
        $endDate = Carbon::now()->addWeek(1)->format($facebookSettings->getFacebookDateFormat());

        // 100 Dollars budget. You have to multiple the value you want by 100
        $budget = 100 * 100;

        $campaign->setData([
            CampaignFields::NAME => 'TEST Campaign',
            CampaignFields::OBJECTIVE => CampaignObjectiveValues::LINK_CLICKS,
            CampaignFields::START_TIME => $startDate,
            CampaignFields::STOP_TIME => $endDate,
            CampaignFields::SPEND_CAP => $budget
        ]);

        try {

            // If the operation is successful the id will be set
            $campaign->create();

            // Grabbing the id.
            $facebookCampaignId = $campaign->{CampaignFields::ID};
            var_dump($facebookCampaignId);
        } catch (RequestException $requestException) {

            $facebookResponse = $requestException->getResponse();

            if (!empty($facebookResponse)) {
                var_dump($facebookResponse->getBody());
            }

        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }

    }


}

FacebookCampaignCreation::main();

