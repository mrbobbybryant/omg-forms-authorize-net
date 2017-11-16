<?php
namespace OMGForms\Authorize\ProcessCard;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use OMGForms\Authorize\TransactionErrors;

define("AUTHORIZENET_LOG_FILE", "phplog");

function process_card( $data ) {
	$apiKey   = get_option( 'authorize_net_api_key' );
	$apiToken = get_option( 'authorize_net_api_token' );

	if ( empty( $apiKey ) ) {
		return new \WP_Error(
			'authorize-net-error',
			'You must set the API Key before you Authorize.net form will work.',
			array( 'status' => 400 )
		);
	}

	if ( empty( $apiToken ) ) {
		return new \WP_Error(
			'authorize-net-error',
			'You must set the API Token before you Authorize.net form will work.',
			array( 'status' => 400 )
		);
	}

	$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	$merchantAuthentication->setName( $apiKey );
	$merchantAuthentication->setTransactionKey( $apiToken );

	$refId = 'ref' . time();

	// Create the payment data for a credit card
	$creditCard = new AnetAPI\CreditCardType();
	$creditCard->setCardNumber( $data['card_number'] );
	$creditCard->setExpirationDate( $data['expiration_date'] );
	$creditCard->setCardCode( $data['card_code'] );
	$paymentOne = new AnetAPI\PaymentType();
	$paymentOne->setCreditCard( $creditCard );

	// Add the payment data to a paymentType object
	$paymentOne = new AnetAPI\PaymentType();
	$paymentOne->setCreditCard( $creditCard );

	//Set Customer Address
	$customerAddress = new AnetAPI\CustomerAddressType();
	$customerAddress->setFirstName( $data['first_name'] );
	$customerAddress->setLastName( $data['last_name'] );

	if ( isset( $data['company'] ) ) {
		$customerAddress->setCompany( $data['company'] );
	}

	$customerAddress->setAddress( $data['address'] );
	$customerAddress->setCity( $data['city'] );
	$customerAddress->setState( $data['state'] );
	$customerAddress->setZip( $data['zip_code'] );

	if ( ! isset( $data['country'] ) ) {
		$customerAddress->setCountry( 'USA' );
	} else {
		$customerAddress->setCountry( $data['country'] );
	}

	// Create a TransactionRequestType object and add the previous objects to it
	$transactionRequestType = new AnetAPI\TransactionRequestType();
	$transactionRequestType->setTransactionType( "authCaptureTransaction" );
	$transactionRequestType->setAmount( $data['transaction_amount'] );
	$transactionRequestType->setPayment( $paymentOne );
	$transactionRequestType->setBillTo( $customerAddress );

	// Create a transaction
	$transactionRequestType = new AnetAPI\TransactionRequestType();
	$transactionRequestType->setTransactionType( "authCaptureTransaction" );
	$transactionRequestType->setAmount( $data[ 'transaction_amount' ] );
	$transactionRequestType->setPayment( $paymentOne );
	$request = new AnetAPI\CreateTransactionRequest();
	$request->setMerchantAuthentication( $merchantAuthentication );
	$request->setRefId( $refId );
	$request->setTransactionRequest( $transactionRequestType );
	$controller = new AnetController\CreateTransactionController( $request );
	$response   = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX );

	if ( $response != null ) {

		$tresponse = $response->getTransactionResponse();
		if ( ( $tresponse != null ) && ( $tresponse->getResponseCode() == "1" ) ) {
			return $tresponse;
		} else {
			if ($tresponse->getErrors() != null) {
				return TransactionErrors\handle_authorize_net_form_errors( $tresponse->getErrors()[0] );
//				echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
//				echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
			}

//			return new \WP_Error(
//				'authorize-net-card-error',
//				'Charge Credit Card ERROR :  Invalid response',
//				array( 'status' => 400 )
//			);
		}
	} else {
		return new \WP_Error(
			'authorize-net-card-error',
			'Charge Credit Card Null response returned',
			array( 'status' => 400 )
		);
	}
}