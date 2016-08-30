<?php
namespace Assessment\V1\Rpc\Deposit;

class DepositControllerFactory
{
    public function __invoke($controllers)
    {
    	$services = $controllers->getServiceLocator();
    	$adapter = $services->get('Sqlite');
    	
        return new DepositController($adapter);
    }
}
