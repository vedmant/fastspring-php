<?php

/**
 * Class FastSpring_Helper
 *
 * Subscriptions helper class
 */
class FastSpring_Helper
{

	/**
	 * Check if user subscribed
	 *
	 * @param  $user_id
	 * @return boolean
	 */
	public static function is_subscribed($user_id)
	{
		$subscription_data = self::get_subscription($user_id);

		// Update subscription with session
		if(! isset($_SESSION['subscription_update']))
			$subscription_data = self::update_subscription($user_id);

		if($subscription_data && 'active' == $subscription_data->status)
			return true;

		return false;
	}

	/**
	 * Update subsciption info from Fastspring
	 *
	 * @param $user_id
	 * @return bool|FsprgSubscription
	 * @throws FsprgException
	 */
	public static function update_subscription($user_id)
	{
		global $fastspring;

		$subscription_data = self::get_subscription($user_id);
		if(! $subscription_data) return false;

		$updated_subscription_data = $fastspring->getSubscription($subscription_data->reference);

		self::save_subscription($user_id, $updated_subscription_data);

		return $updated_subscription_data;
	}

	/**
	 * Get subscription by user_id
	 *
	 * @param $user_id
	 * @return bool|mixed
	 */
	public static function get_subscription($user_id)
	{
		$file = CUSTOMER_DATA_DIR."/$user_id.txt";
		if(file_exists($file)) {
			$subscription_data = json_decode(file_get_contents($file));
			if($subscription_data) return $subscription_data;
		}
		return false;
	}

	/**
	 * Get subscription referrence
	 *
	 * @param $user_id
	 * @return bool
	 */
	public static function get_subscription_ref($user_id)
	{
		$subscription_data = self::get_subscription($user_id);

		if($subscription_data) return $subscription_data->reference;

		return false;
	}

	/**
	 * Save subscription
	 *
	 * @param $user_id
	 * @param $subscription_data
	 */
	public static function save_subscription($user_id, $subscription_data)
	{
		$file = CUSTOMER_DATA_DIR."/$user_id.txt";

		unset($_SESSION['subscription_update']);
		file_put_contents($file, json_encode($subscription_data));
	}

	/**
	 * Delete subsctiption
	 *
	 * @param $user_id
	 */
	public static function delete_subscription($user_id)
	{
		$file = CUSTOMER_DATA_DIR."/$user_id.txt";

		unset($_SESSION['subscription_update']);
		unlink($file);
	}

}


/**
 * Class Arr
 *
 * Arrays helper class
 */
class Arr
{
	/**
	 * Get array key value or default
	 *
	 * @param  $array
	 * @param  $key
	 * @param  $default
	 * @return
	 */
	public static function get($array, $key, $default = null)
	{
		return isset($array[$key]) ? $array[$key] : $default;

	}

}

