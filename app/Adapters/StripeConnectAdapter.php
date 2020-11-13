<?php
namespace App\Adapters;

use Stripe\Account;
use Stripe\Stripe;
use Stripe\AccountLink;
use Stripe\PaymentIntent;

class StripeConnectAdapter{
    // private Stripe $stripe;
    private Account $account;

    public function __construct($apiKey = null, $accountConfig = null){
        Stripe::setApiKey($apiKey ?? config('app.stripe_key'));
        $this->account = Account::create($accountConfig ?? [
            'country' => 'US',
            'type' => 'express'
        ]);
    }

    public function createAccountLink($accountId, $return_uri, $refresh_uri){
        return AccountLink::create([
            'account' => $accountId,
            'refresh_url' => $refresh_uri,
            'return_uri' => $return_uri,
            'type' => 'account_onboarding'
        ]);
    }

    public function createPaymentIntent($accountID, $amount, $currency = null){
        return PaymentIntent::create([
            'payment_method_types' => ['card'],
            'amount' => $amount,
            'currency' => $currency ?? 'usd',
            'transfer_data' => [
                'destination' => $accountID
            ]
        ]);
    }
}
