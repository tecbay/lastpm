<?php

namespace App\Http\Controllers\User;

use App\Models\Plan;
use App\PayableByPaypal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller {
	use PayableByPaypal;

	public function subscribe( Plan $plan ) {
		$description = 'Thanks for subscribing' . $plan->name;

		return $this->payWithpaypal( $plan, 'subscribe.confirm', $description, 'subscribe' );
	}

	public function confirmSubscribe() {
		$planId   = Session::get( 'plan_id' );
		$approved = $this->getPaymentStatus();

		if ( $approved ) {
			auth()->user()->update( [
				'plan_id'       => $planId,
				'is_subscriber' => true,
				'start_at'      => Carbon::now(),
				'end_at'        => ( Carbon::now() )->addDay( 30 )
			] );
			Session::flush( 'success', 'Subscription successful.' );
		} else {
			Session::flush( 'error', 'Subscription failed.' );
		}

		return redirect( 'user.home' );
	}

	public function upgrade( Plan $plan ) {
		if ( ! auth()->user()->is_subscriber() ) {
			return abort(403);
		}
		$CurentPlan = auth()->user()->plan;
//		Todo  validete to upgrade capavality

		$description = 'Thanks for upgrading to' . $plan->name;

		return $this->payWithpaypal( $plan, 'upgrade.confirm', $description, 'upgrade' );

	}

	public function confirmUpgrade() {
		$planId   = Session::get( 'plan_id' );
		$approved = $this->getPaymentStatus();

		if ( $approved ) {
			auth()->user()->update( [
				'plan_id'  => $planId,
				'start_at' => Carbon::now(),
				'end_at'   => ( Carbon::now() )->addDay( 30 )
			] );
			Session::flush( 'success', 'Subscription successful.' );
		} else {
			Session::flush( 'error', 'Subscription failed.' );
		}

		return redirect( 'user.home' );
	}

	public function billpay() {
		$description = 'Thanks for thanka for using ' . config( 'app.name' );

		return $this->payWithpaypal( auth()->user()->plan, 'billpay.confirm', $description, 'billpay' );

	}

	public function confirmBillpay() {
		$approved = $this->getPaymentStatus();

		if ( $approved ) {
			auth()->user()->update( [
				'end_at' => ( new Carbon( auth()->user()->end_at ) )->addDay( 30 )
			] );
			Session::flush( 'success', 'Billpay successful.' );
		} else {
			Session::flush( 'error', 'Billpay failed.' );
		}

		return redirect( 'user.home' );
	}
}
