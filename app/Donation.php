<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
   public $guarded = [];

   public function donateOneTime($request)
   {
   	if($request->amount == null || $request->amount == '' || blank($request->amount))
   	{
   		$request->amount = 1;
   	}
   	$centAmount = $request->amount * 100;
   	\Stripe\Stripe::setApiKey(\App\Option::getOption('STRIPE_SC_KEY'));
   	$customer = \Stripe\Customer::create([
      'email' => $request->stripeEmail,
      'source'  => $request->stripeToken,
	  ]);

	  $charge = \Stripe\Charge::create([
	      'customer' => $customer->id,
	      'amount'   => (int) $centAmount,
	      'currency' => 'GBP',
	  ]);

	  self::create([
	  	'transaction_id'	=>	$charge->id,
	  	'email'				=>	$request->stripeEmail,
	  	'amount'			=>	$request->amount,
	  	'type'				=>	'onetime',
	  ]);
   }

   public function monthlySubscription($request)
   {
      if($request->amount == null || $request->amount == '' || blank($request->amount))
      {
         $request->amount = 1;
      }
      $centAmount = $request->amount * 100;

      \Stripe\Stripe::setApiKey(\App\Option::getOption('STRIPE_SC_KEY'));

      $plan = \Stripe\Plan::create(array(
            "product" => [
                "name" => "Islamic Resource Hub - Monthly Donation"
            ],
            "nickname" => "IRH-Monthly-Plan",
            "interval" => "month",
            "interval_count" => "1",
            "currency" => "GBP",
            "amount" => $centAmount,
      ));

      $customer = \Stripe\Customer::create([
      'email' => $request->stripeEmail,
      'source'  => $request->stripeToken,
     ]);

      $subscription = \Stripe\Subscription::create(array(
            "customer" => $customer->id,
            "items" => array(
                array(
                    "plan" => $plan->id,
                ),
            ),
        ));

     self::create([
      'transaction_id'  => $subscription->id,
      'email'           => $request->stripeEmail,
      'amount'       => $request->amount,
      'type'            => 'monthly_subscription'
     ]);
   }
}
