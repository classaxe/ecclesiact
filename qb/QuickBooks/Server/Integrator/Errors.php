<?php

/**
 * 
 * 
 * @author Keith Palmer <keith@consolibyte.com>
 * @license LICENSE.txt 
 * 
 * @package QuickBooks
 * @subpackage Server
 */

/**
 * 
 */
class QuickBooks_Server_Integrator_Errors
{
	/**
	 *
	 * Error message: 
	 * 	"3170: There was an error when modifying a data extension named 
	 * 	&quot;e-mail&quot;.  QuickBooks error message: This list has been 
	 * 	modified by another user."
	 *
	 * Solution: 
	 * 	Send the request again, it usually goes through the second time.
	 * 
	 * 
	 */
	static public function e3170_errorsaving($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg, $config)
	{
		switch ($action)
		{
			case QUICKBOOKS_MOD_DATAEXT:
				
				// Ignore it for now... oops!
				return true;
			default:
				return false;
		}
		
		// (default clause for switch() statement)
	}	
	
	/**
	 *
	 *
	 *
	 */
	static public function e3180_errorsaving($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg, $config)
	{
		switch ($action)
		{
			case QUICKBOOKS_ADD_DATAEXT:
				
				// Ignore it, this happens when we try to DataExtAdd a DataExt that already exists
				return true;
			default:
				return false;
		}
		
		// (default clause for switch() statement)
	}
	
	static public function e3200_editsequence($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg, $config)
	{
		switch ($action)
		{
			case QUICKBOOKS_MOD_CUSTOMER:
				
				// EditSequence for this customer is out-of-date, query for the customer to get the latest EditSequence, and re-send
				return QuickBooks_Server_Integrator_Callbacks::integrateQueryCustomer($ID);
			default:
				return false;
		}
	}
	
	/**
	 * 
	 * 
	 * 
	 * 
	 */
	static public function e3100_alreadyexists($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg, $config)
	{
		//print('exists!');
		
		// These are special-case handlers, handle these by querying
		switch ($ID)
		{
			case QUICKBOOKS_INTEGRATOR_COUPON_ID:
			case QUICKBOOKS_INTEGRATOR_SHIPPING_ID:
			case QUICKBOOKS_INTEGRATOR_HANDLING_ID:
			case QUICKBOOKS_INTEGRATOR_DISCOUNT_ID:
				
				// @TODO Fix this... I'm not sure whether we should issue another
				//	query (havn't we queried already...?) or just ignore this because
				//	we'll refer to these items by FullName in the requests, so if
				//	it already exists we're golden, or...? 
				
				return true;
				
				switch ($action)
				{
					case QUICKBOOKS_ADD_SERVICEITEM:
						
						break;
					case QUICKBOOKS_ADD_DISCOUNTITEM:
						
						break;
					case QUICKBOOKS_ADD_OTHERCHARGEITEM:
							
						break;
				}
				
				return true;
		}
		
		switch ($action)
		{
			case QUICKBOOKS_ADD_PAYMENTMETHOD:
				
				break;
			case QUICKBOOKS_ADD_SHIPMETHOD:
				
				break;
			case QUICKBOOKS_ADD_NONINVENTORYITEM:
				
				break;
			case QUICKBOOKS_ADD_INVENTORYITEM:
				
				break;
			case QUICKBOOKS_ADD_SERVICEITEM:
				
				
				
				return true;
			case QUICKBOOKS_ADD_CUSTOMER:
				//print('customer!');
				break;
		}
		
		return false;
	}
	
	/**
	 * 
	 * 
	 * 
	 */	
	static public function e_catchall($requestID, $user, $action, $ident, $extra, &$err, $xml, $errnum, $errmsg, $config)
	{
		if (!empty($config['_error_email']))
		{
			$msg = '';
			$msg .= 'Error number: ' . $errnum . "\r\n";
			$msg .= 'Error message: ' . $errmsg . "\r\n";
			$msg .= "\r\n";
			$msg .= 'Action: ' . $action . "\r\n";
			$msg .= 'Ident: ' . $ident . "\r\n";
			$msg .= "\r\n";
			$msg .= 'Extra: ' . print_r($extra, true) . "\r\n";
			$msg .= "\r\n";
			$msg .= 'qbXML: ' . $xml . "\r\n";
			
			mail($config['_error_email'], $config['_error_subject'], $msg, 'From: ' . $config['_error_from'] . "\r\n");
		}
		
		return false;
	}
}
