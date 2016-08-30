<?php
namespace Assessment\V1\Rest\Wallets;

use Zend\Paginator\Adapter\DbTableGateway as TableGatewayPaginator;
use ZF\Apigility\DbConnectedResource;

use Assessment\V1\Rest\BaseResource;

// This throws unknown errors without any description or logs
//class WalletsResource extends BaseResource 
class WalletsResource extends DbConnectedResource
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

        if (isset($data['user_id'])) $filter['user_id'] = $data['user_id'];
        if (isset($data['bonus_id'])) $filter['bonus_id'] = $data['bonus_id'];
        if (isset($data['balance'])) $filter['balance'] = $data['balance'];
        if (isset($data['original'])) $filter['original'] = $data['original'];
        if (isset($data['currency'])) $filter['currency'] = $data['currency'];
        if (isset($data['active'])) $filter['active'] = $data['active'];
        if (isset($data['bonus'])) $filter['bonus'] = $data['bonus'];

        // Somewhere here I should insert the user and bonus data, but can't find how
        $paginatorAdapter = new TableGatewayPaginator($this->table, $filter, $order);
        $collection = new WalletsCollection($paginatorAdapter);

        return $collection;
    }

    /**
     * Fetch an existing resource.
     *
     * @param int|string $id Identifier of resource.
     * @return array|object Resource.
     * @throws DomainException if the resource is not found.
     */
    public function fetch($wallet_id)
    {
        $resultSet = $this->table->select(['id' => $wallet_id]);

        if (0 === $resultSet->count()) throw new DomainException('Item not found', 404);

        $wallet = $resultSet->current();

        // After lots of headbanging I've decided to use pure SQL, because DbSelect would cause an unknown error, no matter how it was used
        $sql = 'SELECT * FROM users WHERE id = ?';
        $resultset = $this->table->adapter->query($sql, [$wallet['user_id']]);
        $user = $resultset->toArray();

        // Don't want to show off the password
        unset($user[0]['password']);

        $wallet['user'] = $user[0];

        if ($wallet['bonus_id'] == null) $wallet['bonus'] = null;
        else {
            $sql = 'SELECT * FROM bonuses WHERE id = ?';
            $resultset = $this->table->adapter->query($sql, [$wallet['bonus_id']]);
            $bonus = $resultset->toArray();

            $wallet['bonus'] = $bonus[0];
        }

        return $wallet;
    }
}