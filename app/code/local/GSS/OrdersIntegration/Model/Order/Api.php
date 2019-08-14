<?php
/**
 * GSS
 *
 * NOTICE OF LICENSE
 *
 * This file is part of Order Integration.
 *
 * Foobar is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *  
 * Order Integration is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @category    GSS
 * @package     GSS_OrdersIntegration
 * @copyright   Copyright (c) 2016 SweetSpot Group Ltd (http://www.gosweetspot.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class GSS_OrdersIntegration_Model_Order_Api extends Mage_Sales_Model_Api_Resource
{
	/**
	 * Initialize attributes map
	 */
	public function __construct()
	{
		$this->_attributesMap = array(
				'order' => array('order_id' => 'entity_id'),
				'order_address' => array('address_id' => 'entity_id'),
				'order_payment' => array('payment_id' => 'entity_id')
		);
	}
	
	public function getOrders($statuses, $fromDate, $toDate, $pageLimit, $page)
	{
	  	/* Format our dates */
	  	$magefromDate = date('Y-m-d H:i:s', strtotime($fromDate));
	  	$magetoDate = date('Y-m-d H:i:s', strtotime($toDate));
	
	  	$arr_status = array();
	  	if($statuses == ""){
			$orderStatusCollection = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
			foreach($orderStatusCollection as $orderStatus) {
			    $arr_status[] = array ('status' => $orderStatus['status']);
			}
	  	}
	  	else {
	  		$arr_status = explode(",", $statuses);
	  	}
	  	//Get country list
	  	$countryList = Mage::getModel('directory/country')->getResourceCollection();
	  	
	    $arr_orders=array();
	    $orders=Mage::getModel('sales/order')
					->getCollection()
					->addAttributeToFilter('status', array('in' => $arr_status))
					->addAttributeToFilter('created_at', array('from'=>$magefromDate, 'to'=>$magetoDate))
					->addAttributeToSort('entity_id', 'DESC')
	                ->setPageSize($pageLimit)
	            	->setCurPage($page);

		foreach ($orders as $order) {
			if ($order->getGiftMessageId() > 0) {
				$order->setGiftMessage(
						Mage::getSingleton('giftmessage/message')->load($order->getGiftMessageId())->getMessage()
						);
			}
			
			$result = $this->_getAttributes($order, 'order');
			$shipping_address = $this->_getAttributes($order->getShippingAddress(), 'order_address');
			foreach($countryList as $country) {
				if ($shipping_address['country_id'] == $country->getCountryId()) {
					$shipping_address['country_id'] = $country->getData('country_code');
					break;
				}
			}
			$result['shipping_address'] = $shipping_address;
			$result['billing_address']  = $this->_getAttributes($order->getBillingAddress(), 'order_address');
			$result['items'] = array();
			
			foreach ($order->getAllItems() as $item) {
				if ($item->getGiftMessageId() > 0) {
					$item->setGiftMessage(
							Mage::getSingleton('giftmessage/message')->load($item->getGiftMessageId())->getMessage()
							);
				}
			
				$result['items'][] = $this->_getAttributes($item, 'order_item');
			}
			
			$result['payment'] = $this->_getAttributes($order->getPayment(), 'order_payment');
			
			$result['status_history'] = array();
			
			foreach ($order->getAllStatusHistory() as $history) {
				$result['status_history'][] = $this->_getAttributes($history, 'order_status_history');
			}

			$arr_orders[] = $result;
		}
					
		return $arr_orders;
  	}
}