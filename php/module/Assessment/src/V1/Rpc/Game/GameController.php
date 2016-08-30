<?php
namespace Assessment\V1\Rpc\Game;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use Zend\Db\Adapter\Adapter;

class GameController extends AbstractActionController
{
	protected $adapter;

	// Because it's easier to use them as class properties than to pass the data back and forth or even make many SQL queries
	protected $fiatWallet;
	protected $bonusWallets;
	protected $id;

	public function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}

    public function gameAction()
    {
    	$data = $this->bodyParams();
    	$bet = (int) $data['bet']; // Casting to int because it comes in as string, and it looks nicer in the output
    	$this->id = $data['user_id'];

		$sql = 'SELECT * FROM users WHERE id = ?';
        $user = $this->adapter->query($sql, [$this->id])->toArray(); 

        if ($user == null) return new ViewModel([
					            'status' => 'error',
					            'user_id' => 'This user does not exist',
					        ]);

		$sql = 'SELECT * FROM wallets WHERE user_id = ? AND active = 1 AND bonus = 1 ORDER BY id ASC';
        $this->bonusWallets = $this->adapter->query($sql, [$this->id])->toArray(); 
		$sql = 'SELECT * FROM wallets WHERE user_id = ? AND active = 1 AND bonus = 0 ORDER BY id ASC';
        $this->fiatWallet = $this->adapter->query($sql, [$this->id])->toArray(); 

        if ($this->bonusWallets == null && $this->fiatWallet == null) return new ViewModel([
					            'status' => 'error',
					            'user_id' => 'This user does not have any balance',
					        ]);

    	if (!$this->checkBalance($bet)) return new ViewModel([
					            'status' => 'error',
					            'user_id' => 'This user has insufficient balance',
					        ]);

    	$remaining = $this->deduct($bet);

    	$rand = rand(0, 2); // 1 in 3 chance of winning

    	if ($rand < 1) {
			$remaining = $this->add($bet + 10); // Always win 10

			return new ViewModel([
				'status' => 'success',
				'bet' => $bet,
				'victory' => true,
				'earnings' => 10,
				'remaining' => $remaining,
			]);
		}

		return new ViewModel([
			'status' => 'success',
			'bet' => $bet,
			'victory' => false,
			'remaining' => $remaining,
		]);
    }

    /*
    * Because I thought it would be interesting to do a little more dynamic game
    */
    public function spinAction()
    {
    	$data = $this->bodyParams();
    	$bet = (int) $data['bet']; // Casting to int because it comes in as string, and it looks nicer in the output
    	$this->id = $data['user_id'];
    	$line = $bet * 500;
    	if ($line > 5000) $line = 5000;

		$sql = 'SELECT * FROM users WHERE id = ?';
        $user = $this->adapter->query($sql, [$this->id])->toArray(); 

        if ($user == null) return new ViewModel([
					            'status' => 'error',
					            'user_id' => 'This user does not exist',
					        ]);

		$sql = 'SELECT * FROM wallets WHERE user_id = ? AND active = 1 AND bonus = 1 ORDER BY id ASC';
        $this->bonusWallets = $this->adapter->query($sql, [$this->id])->toArray(); 
		$sql = 'SELECT * FROM wallets WHERE user_id = ? AND active = 1 AND bonus = 0 ORDER BY id ASC';
        $this->fiatWallet = $this->adapter->query($sql, [$this->id])->toArray(); 

        if ($this->bonusWallets == null && $this->fiatWallet == null) return new ViewModel([
					            'status' => 'error',
					            'user_id' => 'This user does not have any balance',
					        ]);

    	if (!$this->checkBalance($bet)) return new ViewModel([
					            'status' => 'error',
					            'user_id' => 'This user has insufficient balance',
					        ]);

    	$remaining = $this->deduct($bet);

    	// Simple game - take a random number between 0 and 100; If the number is smaller or equal to the bet * 5 (max 50)
    	// then the player has won (rand + 25)%, because I'm a generous God
    	$rand = rand(0, 10000);

		if ($rand < $line) {
			$winning = round($bet * (($rand + 25) / 100)) / 100; // Dividing by extra 100 because rand works in cents

			$remaining = $this->add($winning + $bet); // Because we give the bet back if they win, + earnings

			return new ViewModel([
				'status' => 'success',
				'bet' => $bet,
				'victory' => true,
				'earnings' => $winning,
				'remaining' => $remaining,
			]);
		}

		return new ViewModel([
			'status' => 'success',
			'bet' => $bet,
			'victory' => false,
			'remaining' => $remaining,
		]);
    }

    /**
    * Helper function for checking the given user's balance
    *
    * @param $amount The amount to check against
    *
    * @return bool Whether there is sufficient balance or not
    */
    private function checkBalance($amount) {
    	return $this->calcBalance() >= $amount;
    }

    private function calcBalance() {
        $balance = 0;

        // Looping through wallets, in stead of using 'SELECT SUM(balance)' because I want to sum them up later, and 2 query > 3+ queries
        // PHP is cheaper than SQL, I've learned
        foreach ($this->bonusWallets as $wallet) $balance += $wallet['balance'];

    	if ($this->fiatWallet != null) {
    		if (!isset($this->fiatWallet['id'])) $this->fiatWallet = $this->fiatWallet[0];
    		$balance += $this->fiatWallet['balance'];
    	}

    	return $balance;
    }

    /**
    * Helper function for deducting from the given user's balance
    *
    * @param $amount
    *
    * @return float The remaining balance
    */
    private function deduct($amount) 
    {
		$balance = $this->calcBalance();

    	// First deduct from the real wallet
    	if ($this->fiatWallet != null) {
    		$newBalance = $this->fiatWallet['balance'];

			if ($amount < $newBalance) {
				$newBalance -= $amount;
				$balance -= $amount;
				$amount = 0;
			} else {
				$balance -= $newBalance;
				$amount = $amount - $newBalance;
				$newBalance = 0;
			}

    		$sql = 'UPDATE wallets SET balance = ? WHERE id = ?'; // Do not deactivate fiat wallet; That is always active, because c'mon
    		$this->adapter->query($sql, [$newBalance, $this->fiatWallet['id']]);

			$this->fiatWallet['balance'] = $newBalance;
    	}

    	// Then deduct from the bonus wallet(s)
    	if ($amount > 0) {
    		foreach ($this->bonusWallets as &$wallet) {
	    		$newBalance = $wallet['balance'];

				if ($amount < $newBalance) {
					$newBalance -= $amount;
					$balance -= $amount;
					$amount = 0;
				} else {
					$amount = $amount - $newBalance;
					$balance -= $newBalance;
					$newBalance = 0;
				}

				$sql = 'UPDATE wallets SET balance = ?, active = ? WHERE id = ?';
				$this->adapter->query($sql, [$newBalance, $newBalance == 0 ? 0 : 1, $wallet['id']]);

				$wallet['balance'] = $newBalance;

	    		if ($amount <= 0) break;
	    	}
	    }

	    return $balance;
    }

    /**
    * Helper function for adding to the given user's balance
    *
    * @param $amount
    *
    * @return float The remaining balance
    */
    private function add($amount) 
    {
		$balance = $this->calcBalance();

    	// First put money back on bonus accounts, then to the fiat account
		foreach ($this->bonusWallets as &$wallet) {
			$newBalance = $wallet['balance'];
			$orig = $wallet['original'];
			$diff = $orig - $newBalance;

			if ($amount > $diff) {
				$newBalance = $orig; 
				$amount -= $diff;
				$balance += $diff;
			} else {
				$newBalance += $amount;
				$balance += $amount;
				$amount = 0;
			}

			$sql = 'UPDATE wallets SET balance = ?, active = ? WHERE id = ?';
			$this->adapter->query($sql, [$newBalance, $newBalance == 0 ? 0 : 1, $wallet['id']]);

			if ($amount <= 0) break;
		}

		if ($amount > 0) {
			if ($this->fiatWallet != null) {
				$newBalance = $this->fiatWallet['balance'];
				$orig = $this->fiatWallet['original'];
				$diff = $orig - $newBalance;

				if ($amount > $diff) {
					$newBalance += $amount;
					$orig += $amount - $diff;
					$balance += $diff;
				} else {
					$newBalance += $amount;
					$balance += $amount;
				}

				$sql = 'UPDATE wallets SET balance = ?, original = ? WHERE id = ?';
				$this->adapter->query($sql, [$newBalance, $orig, $this->fiatWallet['id']]);
			} else {
				// Technically we shouldn't reach here
				$sql = 'INSRT INTO wallets (user_id, bonus_id, balance, original, currency, active, bonus) VALUES (?, NULL, ?, ?, \'USD\', 1, 0)';
				$this->adapter->query($sql, [$this->id, $amount, $amount]);
			}
		}

		return $balance;
    }
}
