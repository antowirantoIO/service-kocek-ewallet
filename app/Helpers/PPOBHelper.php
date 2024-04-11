<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class PPOBHelper
{
    private static array $response_codes = [
        [
            'code' => '00',
            'description' => 'SUCCESS',
            'solution' => 'Transaction success.'
        ],
        [
            'code' => '06',
            'description' => 'TRANSACTION NOT FOUND',
            'solution' => 'There is no transaction with your inputted ref_id. Please check again your inputted ref_id to find your transaction.'
        ],
        [
            'code' => '07',
            'description' => 'FAILED',
            'solution' => 'Your current transaction has failed. Please try again.'
        ],
        [
            'code' => '10',
            'description' => 'REACH TOP UP LIMIT USING SAME DESTINATION NUMBER IN 1 DAY',
            'solution' => 'Your current destination number top up request is reaching the limit on that day. Please try again tomorrow.'
        ],
        [
            'code' => '12',
            'description' => 'BALANCE MAXIMUM LIMIT EXCEEDED',
            'solution' => 'Your balance has exceeded the maximum limit.'
        ],
        [
            'code' => '13',
            'description' => 'CUSTOMER NUMBER BLOCKED',
            'solution' => 'Your customer number (customer_id) has been blocked. You can change your customer number (customer_id) or contact our Customer Service.'
        ],
        [
            'code' => '14',
            'description' => 'INCORRECT DESTINATION NUMBER',
            'solution' => 'Your customer_id that you’ve inputted isn’t a valid phone number. Please check again your customer_id.'
        ],
        [
            'code' => '16',
            'description' => 'NUMBER NOT MATCH WITH OPERATOR',
            'solution' => 'Your phone number (customer_id) that you’ve inputted doesn’t match with your desired operator (product_code). Please check again your phone number or change your operator.'
        ],
        [
            'code' => '17',
            'description' => 'INSUFFICIENT DEPOSIT',
            'solution' => 'Your current deposit is lower than the product_price you want to buy. You can add more money into your deposit by doing top up on iak.id deposit menu, or if you are in development mode, you can add your development deposit by clicking the + (plus) sign on development deposit menu (https://developer.iak.id/sandbox-report).'
        ],
        [
            'code' => '20',
            'description' => 'CODE NOT FOUND',
            'solution' => 'Your inputted product_code isn’t in the database. Check again your product_code, you can check product_code list by using Pricelist API.'
        ],
        [
            'code' => '39',
            'description' => 'PROCESS',
            'solution' => 'Your current transaction is being processed, please wait until your transaction is fully processed. You can check the status by using check-status API or by receiving a callback (if you use callback).'
        ],
        [
            'code' => '102',
            'description' => 'INVALID IP ADDRESS',
            'solution' => 'Your IP address isn’t allowed to make a transaction. You can add your IP address to your allowed IP address list in https://developer.iak.id/prod-setting.'
        ],
        [
            'code' => '106',
            'description' => 'PRODUCT IS TEMPORARILY OUT OF SERVICE',
            'solution' => 'The product_code that you pick is in non-active status. You can retry your transaction with another product_code that has active status.'
        ],
        [
            'code' => '107',
            'description' => 'ERROR IN XML FORMAT',
            'solution' => 'The body format of your request isn’t correct or there is an error in your body (required, ajax error, etc). Please use the correct JSON or XML format corresponding to your request to API. You can see the required body request for each request in the API Documentation.'
        ],
        [
            'code' => '110',
            'description' => 'SYSTEM UNDER MAINTENANCE',
            'solution' => 'The system is currently under maintenance, you can try again later.'
        ],
        [
            'code' => '117',
            'description' => 'PAGE NOT FOUND',
            'solution' => 'The API URL that you want to hit is not found. Try checking your request URL for any typos or try other API URLs.'
        ],
        [
            'code' => '121',
            'description' => 'MONTHLY TOP UP LIMIT EXCEEDED',
            'solution' => 'You have exceeded the monthly top-up limit.'
        ],
        [
            'code' => '131',
            'description' => 'TOP UP REGION BLOCKED FOR PLAYER',
            'solution' => 'Your current destination number top up request is blocked in that region. Please try again with a different destination number.'
        ],
        [
            'code' => '141',
            'description' => 'INVALID USER ID / ZONE ID / SERVER ID / ROLENAME',
            'solution' => 'Your inputted user ID / Zone ID / Server ID / Role name isn’t valid. Please try again with another user ID / Zone ID / Server ID / Role name. You can check on Inquiry Game Server.'
        ],
        [
            'code' => '142',
            'description' => 'INVALID USER ID',
            'solution' => 'Your current destination number (user id) top up request is invalid. Please try again with a different destination number or try checking for typos in your field.'
        ],
        [
            'code' => '201',
            'description' => 'UNDEFINED RESPONSE CODE',
            'solution' => 'The received response code is undefined yet. Please contact our Customer Service.'
        ],
        [
            'code' => '202',
            'description' => 'MAXIMUM 1 NUMBER 1 TIME IN 1 DAY',
            'solution' => 'You can only top up to a phone number once in a day (based on your developer setting). If you want to allow more than one top up to a phone number, you can go to https://developer.iak.id/ then choose API Setting menu, you can turn on “Allow multiple transactions for the same number” in development or production settings.'
        ],
        [
            'code' => '203',
            'description' => 'NUMBER IS TOO LONG',
            'solution' => 'Your inputted customer ID is too long. Please check again your customer ID.'
        ],
        [
            'code' => '204',
            'description' => 'WRONG AUTHENTICATION',
            'solution' => 'Your sign (signature) field doesn’t contain the right key for your current request. Please check again your sign value.'
        ],
        [
            'code' => '205',
            'description' => 'WRONG COMMAND',
            'solution' => 'The command that you’ve inputted is not a valid command, try check your commands field for typos or try another command.'
        ],
        [
            'code' => '206',
            'description' => 'THIS DESTINATION NUMBER HAS BEEN BLOCKED',
            'solution' => 'The customer_id that you inputted is blocked. You can unblock it by remove customer number blacklist in API Security menu on developer.iak.id (https://developer.iak.id/end-user-blacklist).'
        ],
        [
            'code' => '207',
            'description' => 'MAXIMUM 1 NUMBER WITH ANY CODE 1 TIME IN 1 DAY',
            'solution' => 'You’ve already done a transaction today. Please do another transaction tomorrow, or disable the high restriction setting in https://developer.iak.id/prod-setting.'
        ]
    ];

    private static array $operator_prefixes = [
        '0814' => 'INDOSAT',
        '0815' => 'INDOSAT',
        '0816' => 'INDOSAT',
        '0855' => 'INDOSAT',
        '0856' => 'INDOSAT',
        '0857' => 'INDOSAT',
        '0858' => 'INDOSAT',
        '0817' => 'XL',
        '0818' => 'XL',
        '0819' => 'XL',
        '0859' => 'XL',
        '0878' => 'XL',
        '0877' => 'XL',
        '0838' => 'AXIS',
        '0837' => 'AXIS',
        '0831' => 'AXIS',
        '0832' => 'AXIS',
        '0812' => 'TELKOMSEL',
        '0813' => 'TELKOMSEL',
        '0852' => 'TELKOMSEL',
        '0853' => 'TELKOMSEL',
        '0821' => 'TELKOMSEL',
        '0823' => 'TELKOMSEL',
        '0822' => 'TELKOMSEL',
        '0851' => 'TELKOMSEL',
        '0881' => 'SMARTFREN',
        '0882' => 'SMARTFREN',
        '0883' => 'SMARTFREN',
        '0884' => 'SMARTFREN',
        '0885' => 'SMARTFREN',
        '0886' => 'SMARTFREN',
        '0887' => 'SMARTFREN',
        '0888' => 'SMARTFREN',
        '0896' => 'THREE',
        '0897' => 'THREE',
        '0898' => 'THREE',
        '0899' => 'THREE',
        '0895' => 'THREE',
        '085154' => 'byU',
        '085155' => 'byU',
        '085156' => 'byU',
        '085157' => 'byU',
        '085158' => 'byU'
    ];

    public static function getPrepaidProductsPPOB()
    {
        $sign = md5(config('services.biller.username') . config('services.biller.api_key') . 'pl');

        $response = Http::post(
            config('services.biller.prepaid_base_url') . '/pricelist',
            [
                'status' => 'all',
                'username' => config('services.biller.username'),
                'sign' => $sign
            ]
        );

        return $response->json();
    }

    public static function getPostpaidProductsPPOB()
    {
        $sign = md5(config('services.biller.username') . config('services.biller.api_key') . 'pl');

        $response = Http::post(
            config('services.biller.postpaid_base_url') . '/bill/check',
            [
                'commands' => 'pricelist-pasca',
                'status' => 'all',
                'username' => config('services.biller.username'),
                'sign' => $sign
            ]
        );

        return $response->json();
    }

    public static function getCustomerPlnPrepaid(int $customer_id)
    {
        $sign = md5(config('services.biller.username') . config('services.biller.api_key') . $customer_id);

        $response = Http::post(
            config('services.biller.prepaid_base_url') . '/inquiry-pln',
            [
                'customer_id' => $customer_id,
                'username' => config('services.biller.username'),
                'sign' => $sign
            ]
        );

        return $response->json();
    }

    public static function identifiedOperatorByNumberPhone(string $phone_number): ?string
    {
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

        $prefix = substr($phone_number, 0, 6);
        if (isset(self::$operator_prefixes[$prefix])) {
            return self::$operator_prefixes[$prefix];
        }

        $prefix = substr($phone_number, 0, 4);
        if (isset(self::$operator_prefixes[$prefix])) {
            return self::$operator_prefixes[$prefix];
        }

        return null;
    }

    public static function formatErrorsFromBiller(
        string $response_code
    ): array
    {
        return collect(self::$response_codes)->where('code', $response_code)->first();
    }
}
