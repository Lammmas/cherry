<?php
namespace Assessment\V1\Rest\Users;

use Zend\Paginator\Adapter\DbTableGateway as TableGatewayPaginator;
use ZF\Apigility\DbConnectedResource;

use Assessment\V1\Rest\BaseResource;

// This throws unknown errors without any description or logs
//class UsersResource extends BaseResource 
class UsersResource extends DbConnectedResource
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

    	if (isset($data['email'])) $filter['email LIKE ?'] = "%{$data['email']}%";
    	if (isset($data['name'])) $filter['name LIKE ?'] = "%{$data['name']}%";
    	if (isset($data['age'])) $filter['age'] = (int) $data['age'];
    	if (isset($data['gender'])) $filter['gender'] = $data['gender'];

        $paginatorAdapter = new TableGatewayPaginator($this->table, $filter, $order);
        $collection = new UsersCollection($paginatorAdapter);

        return $collection;
    }
}