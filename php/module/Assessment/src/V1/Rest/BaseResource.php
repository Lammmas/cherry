<?php
namespace Assessment\V1\Rest;

use ZF\Apigility\DbConnectedResource;

class BaseResource extends DbConnectedResource {
	/**
	* Helper function for parsing the Sorting info from the query parameters
	* 
	* @param $data Query data
	* 
	* @return string 
	*/
	protected parseOrder(array $data) {
    	$direction = '';

    	if (isset($data['order']) && in_array(strtoupper($data['order']), ['ASC', 'DESC'])) $direction = strtoupper($data['order']);

    	$order = $data['sort'] ?? 'id';
    	$order .= ' ' . $direction;

    	return $order;
	}
}