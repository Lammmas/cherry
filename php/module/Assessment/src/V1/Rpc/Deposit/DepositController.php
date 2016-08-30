<?php
namespace Assessment\V1\Rpc\Deposit;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use Zend\Db\Adapter\Adapter;

class DepositController extends AbstractActionController
{
	protected $adapter;

	public function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}

    public function depositAction()
    {
    	$data = $this->bodyParams();
    	$amount = $left = $data['amount'];

		$sql = 'SELECT * FROM users WHERE id = ?';
        $user = $this->adapter->query($sql, [$data['user_id']])->toArray(); 

        if ($user == null) return new ViewModel([
					            'status' => 'error',
					            'user_id' => 'This user does not exist',
					        ]);
        else $user = $user[0];

    	// First find the bonus wallets that need topping up
		$sql = 'SELECT * FROM wallets WHERE user_id = ? AND bonus = 1 AND active = 1 '; // AND currency = 'chocolate'
        $bonusWallets = $this->adapter->query($sql, [$data['user_id']])->toArray();

        if ($bonusWallets != null) {
        	foreach ($bonusWallets as $wallet) {
        		$diff = $wallet['original'] - $wallet['balance'];
        		$deposit = $diff;

        		if ($diff > $left) $deposit = $left;

        		$left -= $deposit;

        		$sql = 'UPDATE wallets SET balance = balance + ?, active = 1 WHERE id = ?';
        		$this->adapter->query($sql, [$deposit, $wallet['id']]);

        		if ($left <= 0) break;
        	}
        }

        // Then, if there's anything left, find the main fiat wallet and top it up
        if ($left > 0) {
			$sql = 'SELECT * FROM wallets WHERE user_id = ? AND bonus = 0 AND active = 1';
	        $wallet = $this->adapter->query($sql, [$data['user_id']])->toArray()[0];
	        $diff = $wallet['original'] - $wallet['balance'];
	        $extra = 0;

	        if ($diff > 0 && $diff < $left) $extra = $left - $diff;

	        if ($extra > 0) {
	    		$sql = 'UPDATE wallets SET balance = balance + ?, original = original + ?, active = 1 WHERE id = ?';
	    		$this->adapter->query($sql, [$left, $extra, $wallet['id']]);
	        } else {
	    		$sql = 'UPDATE wallets SET balance = balance + ?, active = 1 WHERE id = ?';
	    		$this->adapter->query($sql, [$left, $wallet['id']]);
	    	}
        }

    	$bonus = $this->getBonus($user['id'], $amount);
        $data = [
            'status' => 'success',
            'message' => 'Successfully deposited',
        ];

        if ($bonus) {
            $data['message'] .= ' and earned bonus';
            $data['bonus'] = $bonus;
        }

    	return new ViewModel($data);
    }

    /**
    * Helper function for calculating and applying Deposit bonus for the given user
    * 
    * @param int $userId
    * @param float $amount Amount deposited
    * 
    * @return int Bonus for bonus applied, 0 for no bonus
    */
    private function getBonus(int $userId, float $amount = 0) {
        $sql = 'SELECT * FROM bonuses WHERE trigger = ? AND active = 1';
        $resultset = $this->adapter->query($sql, ['deposit']);

        $available = $resultset->toArray();
        $result = 0;

        if (!$available) return 0;

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
		        	1//(int) ($bonus['type'] == 'real'),
    			];
                $result += $bonus['value'];

		        $this->adapter->query($sql, $params);
        	} else if ($bonus['type'] == 'percent' && $amount > 0) {
        		// In stead of creating a bunch of wallets, it would be better to have only 2 wallets - real and bonus
        		// And have the bonus earnings logged into a separate table
		        $sql = 'INSERT INTO wallets (user_id, bonus_id, balance, original, currency, active, bonus) VALUES (?, ?, ?, ?, ?, ?, ?)';
		        $earned = $amount * $bonus['value'] / 100;
		        $params = [
		        	$userId,
		        	$bonus['id'],
		        	$earned,
		        	$earned,
		        	$bonus['currency'], // This should also be set in bonus, because we could have many different currencies
		        	1,
		        	1//(int) ($bonus['type'] == 'real'),
    			];
                $result += $earned;

		        $this->adapter->query($sql, $params);
        	}
        }

        return $result;
    }
}
