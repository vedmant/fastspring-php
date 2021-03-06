<?php
if(! function_exists("curl_init")) {
	throw new Exception("FastSpring API needs the CURL PHP extension.");
}

/**
 * Fastspring api class
 *
 * Class FastSpring
 */
class FastSpring
{

	private $company_id;
	private $store_id;
	private $api_username;
	private $api_password;

	public $test_mode = false;

	public function __construct($company_id, $store_id, $api_username, $api_password)
	{
		$this->company_id   = $company_id;
		$this->store_id     = $store_id;
		$this->api_username = $api_username;
		$this->api_password = $api_password;
	}

	/**
	 * create new order url and redirect user to it
	 *
	 * @param $product_ref
	 * @param $customer_ref
	 */
	public function createSubscription($product_ref, $customer_ref)
	{
		$url = "http://sites.fastspring.com/".$this->store_id."/product/".$product_ref."?referrer=".$customer_ref;
		$url = $this->addTestMode($url);

		header("Location: $url");
	}

	/**
	 * retrieve subscription data from fastspring API
	 *
	 * @param $subscription_ref
	 * @return FsprgSubscription
	 * @throws FsprgException
	 */
	public function getSubscription($subscription_ref)
	{
		$url = $this->getSubscriptionUrl($subscription_ref);
		$ch  = curl_init($url);

		curl_setopt($ch, CURLOPT_USERPWD, $this->api_username.":".$this->api_password);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$info     = curl_getinfo($ch);

		if($info["http_code"] == 200) {
			set_error_handler("domDocumentErrorHandler");

			try {
				$doc = new DOMDocument();
				$doc->loadXML($response);

				$sub = $this->parseFsprgSubscription($doc);
			} catch(Exception $e) {
				$fsprgEx                 = new FsprgException("An error occurred calling the FastSpring subscription service", 0, $e);
				$fsprgEx->httpStatusCode = $info["http_code"];
			}

			restore_error_handler();
		} else {
			$fsprgEx                 = new FsprgException("An error occurred calling the FastSpring subscription service");
			$fsprgEx->httpStatusCode = $info["http_code"];
			$fsprgEx->errorCode      = $response;
		}

		if(isset($fsprgEx)) {
			throw $fsprgEx;
		}

		return $sub;
	}

	/**
	 * update an existing subscription to fastspring API
	 *
	 * @param $subscriptionUpdate
	 * @return FsprgSubscription
	 * @throws FsprgException
	 */
	public function updateSubscription($subscriptionUpdate)
	{
		$url = $this->getSubscriptionUrl($subscriptionUpdate->reference);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_USERPWD, $this->api_username.":".$this->api_password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $subscriptionUpdate->toXML());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

		$response = curl_exec($ch);
		$info     = curl_getinfo($ch);

		if($info["http_code"] == 200) {
			set_error_handler("domDocumentErrorHandler");

			try {
				$doc = new DOMDocument();
				$doc->loadXML($response);

				$sub = $this->parseFsprgSubscription($doc);
			} catch(Exception $e) {
				$fsprgEx                 = new FsprgException("An error occurred calling the FastSpring subscription service", 0, $e);
				$fsprgEx->httpStatusCode = $info["http_code"];
			}

			restore_error_handler();
		} else {
			$fsprgEx                 = new FsprgException("An error occurred calling the FastSpring subscription service");
			$fsprgEx->httpStatusCode = $info["http_code"];
			$fsprgEx->errorCode      = $response;
		}

		curl_close($ch);

		if(isset($fsprgEx)) {
			throw $fsprgEx;
		}

		return $sub;
	}

	/**
	 * send cancel request for a subscription to fastspring API
	 *
	 * @param $subscription_ref
	 * @return FsprgCancelSubscriptionResponse
	 * @throws FsprgException
	 */
	public function cancelSubscription($subscription_ref)
	{
		$url = $this->getSubscriptionUrl($subscription_ref);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_USERPWD, $this->api_username.":".$this->api_password);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		$response = curl_exec($ch);
		$info     = curl_getinfo($ch);

		if($info["http_code"] == 200) {
			set_error_handler("domDocumentErrorHandler");

			try {
				$doc = new DOMDocument();
				$doc->loadXML($response);

				$sub = $this->parseFsprgSubscription($doc);

				$subResp               = new FsprgCancelSubscriptionResponse();
				$subResp->subscription = $sub;
			} catch(Exception $e) {
				$fsprgEx                 = new FsprgException("An error occurred calling the FastSpring subscription service", 0, $e);
				$fsprgEx->httpStatusCode = $info["http_code"];
			}

			restore_error_handler();
		} else {
			$fsprgEx                 = new FsprgException("An error occurred calling the FastSpring subscription service");
			$fsprgEx->httpStatusCode = $info["http_code"];
			$fsprgEx->errorCode      = $response;
		}

		curl_close($ch);

		if(isset($fsprgEx)) {
			throw $fsprgEx;
		}

		return $subResp;
	}

	/**
	 * send renew request for an on-demand subscription to fastspring API
	 *
	 * @param $subscription_ref
	 * @throws FsprgException
	 */
	public function renewSubscription($subscription_ref)
	{
		$url = $this->getSubscriptionUrl($subscription_ref."/renew");

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_USERPWD, $this->api_username.":".$this->api_password);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$info     = curl_getinfo($ch);

		if($info["http_code"] != 201) {
			$fsprgEx                 = new FsprgException("An error occurred calling the FastSpring subscription service");
			$fsprgEx->httpStatusCode = $info["http_code"];
			$fsprgEx->errorCode      = $response;
		}

		curl_close($ch);

		if(isset($fsprgEx)) {
			throw $fsprgEx;
		}
	}

	/**
	 * compose customer's subscription management url for a given subscription reference
	 *
	 * @param $subscription_ref
	 * @return string
	 */
	private function getSubscriptionUrl($subscription_ref)
	{
		$url = "https://api.fastspring.com/company/".$this->company_id."/subscription/".$subscription_ref;

		$url = $this->addTestMode($url);

		return $url;
	}

	/**
	 * add test parameter to url if test mode enabled
	 *
	 * @param $url
	 * @return string
	 */
	private function addTestMode($url)
	{
		if($this->test_mode) {
			if(strpos($url, '?') != false) {
				$url = $url."&mode=test";
			} else {
				$url = $url."?mode=test";
			}
		}

		return $url;
	}

	/**
	 * parse subscription xml into subscription and customer data
	 *
	 * @param $doc
	 * @return FsprgSubscription
	 */
	private function parseFsprgSubscription($doc)
	{
		$sub = new FsprgSubscription();

		$sub->status        = $doc->getElementsByTagName("status")->item(0)->nodeValue;
		$sub->statusChanged = strtotime($doc->getElementsByTagName("statusChanged")->item(0)->nodeValue);
		$sub->statusReason  = $doc->getElementsByTagName("statusReason")->item(0)->nodeValue;
		$sub->cancelable    = $doc->getElementsByTagName("cancelable")->item(0)->nodeValue;
		$sub->reference     = $doc->getElementsByTagName("reference")->item(0)->nodeValue;
		$sub->test          = $doc->getElementsByTagName("test")->item(0)->nodeValue;

		$customer = new FsprgCustomer();

		$customer->firstName   = $doc->getElementsByTagName("firstName")->item(0)->nodeValue;
		$customer->lastName    = $doc->getElementsByTagName("lastName")->item(0)->nodeValue;
		$customer->company     = $doc->getElementsByTagName("company")->item(0)->nodeValue;
		$customer->email       = $doc->getElementsByTagName("email")->item(0)->nodeValue;
		$customer->phoneNumber = $doc->getElementsByTagName("phoneNumber")->item(0)->nodeValue;

		$sub->customer = $customer;

		$sub->customerUrl    = $doc->getElementsByTagName("customerUrl")->item(0)->nodeValue;
		$sub->productName    = $doc->getElementsByTagName("productName")->item(0)->nodeValue;
		$sub->tags           = $doc->getElementsByTagName("tags")->item(0)->nodeValue;
		$sub->quantity       = $doc->getElementsByTagName("quantity")->item(0)->nodeValue;
		$sub->nextPeriodDate = strtotime($doc->getElementsByTagName("nextPeriodDate")->item(0)->nodeValue);
		$sub->end            = strtotime($doc->getElementsByTagName("end")->item(0)->nodeValue);

		return $sub;
	}
}

/**
 * Fastspring Subscription item
 *
 * Class FsprgSubscription
 */
class FsprgSubscription
{
	public $status;
	public $statusChanged;
	public $statusReason;
	public $cancelable;
	public $reference;
	public $test;
	public $customer;
	public $customerUrl;
	public $productName;
	public $tags;
	public $quantity;
}

/**
 * Fastspring customer item
 *
 * Class FsprgCustomer
 */
class FsprgCustomer
{
	public $firstName;
	public $lastName;
	public $company;
	public $email;
	public $phoneNumber;
}

/**
 * Subscription update item, used with FastSpring::updateSubscription() function
 *
 * Class FsprgSubscriptionUpdate
 */
class FsprgSubscriptionUpdate
{
	public $reference;
	public $productPath;
	public $quantity;
	public $tags;
	public $noEndDate;
	public $coupon;
	public $discountDuration;
	public $proration;

	public function __construct($subscription_ref)
	{
		$this->reference = $subscription_ref;
	}

	public function toXML()
	{
		$xmlResult = new SimpleXMLElement("<subscription></subscription>");

		if($this->productPath) {
			$xmlResult->productPath = $this->productPath;
		}
		if($this->quantity) {
			$xmlResult->quantity = $this->quantity;
		}
		if($this->tags) {
			$xmlResult->tags = $this->tags;
		}
		if(isset($this->noEndDate) && $this->noEndDate) {
			$xmlResult->addChild("no-end-date", null);
		}
		if($this->coupon) {
			$xmlResult->coupon = $this->coupon;
		}
		if($this->discountDuration) {
			$xmlResult->addChild("discount-duration", $this->discountDuration);
		}
		if(isset($this->proration)) {
			if($this->proration) {
				$xmlResult->proration = "true";
			} else {
				$xmlResult->proration = "false";
			}
		}

		return $xmlResult->asXML();
	}
}

/**
 * Cancel subscription response class
 *
 * Class FsprgCancelSubscriptionResponse
 */
class FsprgCancelSubscriptionResponse
{
	public $subscription;
}

/**
 * FastSpring exception
 *
 * Class FsprgException
 */
class FsprgException extends Exception
{
	public $httpStatusCode;
	public $errorCode;
}

/**
 * FastSpring Api custom errors handler
 *
 * @param $number
 * @param $error
 * @throws Exception
 */
function domDocumentErrorHandler($number, $error)
{
	if(preg_match("/^DOMDocument::load\([^:]+: (.+)$/", $error, $m) === 1) {
		throw new Exception($m[1]);
	}
}
