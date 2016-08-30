<?php
namespace Assessment\V1\Rest\Bonuses;

use Zend\Paginator\Adapter\DbTableGateway as TableGatewayPaginator;
use ZF\Apigility\DbConnectedResource;

use Assessment\V1\Rest\BaseResource;

// This throws unknown errors without any description or logs
//class BonusesResource extends BaseResource 
class BonusesResource extends DbConnectedResource
{
    /**
     * Fetch a paginated set of resources.
     *
     * @param array|object $data Query parameters
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($data = [])
    {
    	// Need to force convert $data to array because TableGatewayPaginator takes only an array for $select; Also easier to check
    	$data = (array) $data;
    	$filter = [];
    	$direction = '';

		// Had to copy the parseOrder function here because I don't know why but I couldn't use the BaseResource to inherit this.
    	if (isset($data['order']) && in_array(strtoupper($data['order']), ['ASC', 'DESC'])) $direction = strtoupper($data['order']);

    	$order = $data['sort'] ?? 'id';
    	$order .= ' ' . $direction;

        // It would be better to use the BaseResource with dynamic field filling and smart filtering, ex. value=>50&multiplier=<1
        // But I don't have the time, knowledge nor patience for messing with the BaseResource
        if (isset($data['trigger'])) $filter['trigger'] = $data['trigger'];
        if (isset($data['value'])) $filter['value'] = $data['value'];
        if (isset($data['type'])) $filter['type'] = $data['type'];
        if (isset($data['multiplier'])) $filter['multiplier'] = $data['multiplier'];
        if (isset($data['active'])) $filter['active'] = $data['active'];

        $paginatorAdapter = new TableGatewayPaginator($this->table, $filter, $order);
        $collection = new BonusesCollection($paginatorAdapter);

        return $collection;
    }
}