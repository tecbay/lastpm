<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Transaction as Tran;

trait PayableByPaypal {

	public function __construct() {
		/** PayPal api context **/
		$paypal_conf        = \Config::get( 'paypal' );
		$this->_api_context = new ApiContext( new OAuthTokenCredential(
				$paypal_conf['client_id'],
				$paypal_conf['secret'] )
		);
		$this->_api_context->setConfig( $paypal_conf['settings'] );
	}


	/**
	 * @param Plan $plan
	 * @param $statusRoute
	 * @param $description
	 * @param $type valid types are subscribe,billpay,upgrade
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function payWithpaypal( Plan $plan, $statusRoute, $description, $type ) {

		$payer = new Payer();
		$payer->setPaymentMethod( 'paypal' );
		$price    = $plan->price;//plan price
		$planName = $plan->name;//plan price

		$item = new Item();
		$item->setName( $planName )/** item name **/
		     ->setCurrency( 'USD' )
//		     ->setQuantity( 1 )
             ->setPrice( $price );
		/** unit price **/
		$item_list = new ItemList();
		$item_list->setItems( array( $item ) );

		$amount = new Amount();
		$amount->setCurrency( 'USD' )
		       ->setTotal( $price );

		$transaction = new Transaction();
		$transaction->setAmount( $amount )
		            ->setItemList( $item_list )
		            ->setDescription( $description );

		$redirect_urls = new RedirectUrls();
		$redirect_urls->setReturnUrl( route( $statusRoute ) )/** Specify return URL **/
		              ->setCancelUrl( route( $statusRoute ) );

		$payment = new Payment();
		$payment->setIntent( 'Sale' )
		        ->setPayer( $payer )
		        ->setRedirectUrls( $redirect_urls )
		        ->setTransactions( array( $transaction ) );
		/** dd($payment->create($this->_api_context));exit; **/
		try {
			$payment->create( $this->_api_context );
		} catch ( \PayPal\Exception\PPConnectionException $ex ) {
			if ( \Config::get( 'app.debug' ) ) {
				\Session::put( 'error', 'Connection timeout' );

				return Redirect::route( 'paywithpaypal' );
			} else {
				\Session::put( 'error', 'Some error occur, sorry for inconvenient' );

				return Redirect::route( 'user.home' );
			}
		}
		foreach ( $payment->getLinks() as $link ) {
			if ( $link->getRel() == 'approval_url' ) {
				$redirect_url = $link->getHref();
				break;
			}
		}

		/** add payment ID to session **/
		Session::put( 'paypal_payment_id', $payment->getId() );
		Session::put( 'payment_type', $type );
		Session::put( 'plan_id', $plan->id);

		if ( isset( $redirect_url ) ) {
			/** redirect to paypal **/
			return Redirect::away( $redirect_url );
		}
		\Session::put( 'error', 'Unknown error occurred' );

		return Redirect::route( 'user.home' );
	}


	/**
	 * @return mixed
	 */
	public function getPaymentStatus() {
		/** Get the payment ID before session clear **/
		$payment_id = Session::get( 'paypal_payment_id' );
		$type       = Session::get( 'payment_type' );
		/** clear the session payment ID **/
		Session::forget( 'paypal_payment_id' );

		if ( empty( Input::get( 'PayerID' ) ) || empty( Input::get( 'token' ) ) ) {
			\Session::flash( 'error', 'Payment failed' );

			return false;
		}
		$payment   = Payment::get( $payment_id, $this->_api_context );
		$execution = new PaymentExecution();
		$execution->setPayerId( Input::get( 'PayerID' ) );
		/**Execute the payment **/
		$result = $payment->execute( $execution, $this->_api_context );

		if ( $result->getState() == 'approved' ) {
			$tran  = $result->getTransactions();
			$total = $tran[0]->getAmount()->getTotal();

			\Session::flash( 'success', 'Payment success' );
			auth()->user()->transection()->save( [
				'amount'   => $total,
				'type'     => $type,
				'payer_id' => $payment_id
			] );

			return true;
		}
		\Session::put( 'error', 'Payment failed' );

		return false;
	}

}



