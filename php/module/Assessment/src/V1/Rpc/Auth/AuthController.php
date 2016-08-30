<?php
namespace Assessment\V1\Rpc\Auth;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use Zend\Db\Adapter\Adapter;

class AuthController extends AbstractActionController
{
	protected $adapter;

	public function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}

	/**
	* Authenticates the user via email and password. 
	* It would be better to use OAuth and do it smart, but for the purpose of this exercise I'm doing it easy
	*/
    public function authAction()
    {
    	$data = $this->bodyParams();

        $sql = 'SELECT * FROM users WHERE email = ?';
        $resultset = $this->adapter->query($sql, [$data['email']]);

        $user = $resultset->toArray(); 

		// AKA the email does not exist
        if ($user == null) return new ViewModel([
					            'status' => 'error',
					            'email' => 'This email does not exist',
					        ]);
        else $user = $user[0]; // Because the querier returns an array of arrays, even if it's a single result

        $valid = password_verify($data['password'], $user['password']);

        if ($valid) {
        	$bonus = $this->getBonus($user['id']);

	    	return new ViewModel([
	            'status' => 'success',
	            'message' => 'Successfully authenticated' . ($bonus ? ' and earned bonus' : ''),
	        ]);
	    } 
	    else return new ViewModel([
	            'status' => 'error',
	            'email' => 'This password is invalid',
	        ]);
    }

    /**
    * Helper function for calculating and applying Login bonus for the given user
    * 
    * @param int $userId
    * 
    * @return bool True for bonus applied, False for no bonus
    */
    private function getBonus(int $userId) {
        $sql = 'SELECT * FROM bonuses WHERE trigger = ? AND active = 1';
        $resultset = $this->adapter->query($sql, ['login']);

        $available = $resultset->toArray();

        if (!$available) return false;

        foreach ($available as $bonus) {
        	if ($bonus['type'] != 'percent') {
        		// In stead of creating a bunch of wallets, it would be better to have only 2 wallets - real and bonus
        		// And have the bonus earnings logged into a separate table
		        $sql = 'INSERT INTO wallets (user_id, bonus_id, balance, original, currency, active, bonus) VALUES (?, ?, ?, ?, ?, ?, ?)';
		        $params = [
		        	$userId,
		        	$bonus['id'],
		        	$bonus['value'],
		        	$bonus['value'],
		        	$bonus['currency'], // This should also be set in bonus, because we could have many different currencies
		        	1,
		        	(int) ($bonus['type'] == 'real'),
    			];

    			// Can do some error checking, AKA did the query really execute, etc
		        //$resultset = $this->adapter->query($sql, $params);
		        //if ($resultset->count() != 1) ...

		        $this->adapter->query($sql, $params);
        	}
        }

        return true;
    }
}
