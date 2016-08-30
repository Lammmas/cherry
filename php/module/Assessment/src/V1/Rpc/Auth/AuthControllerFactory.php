<?php
namespace Assessment\V1\Rpc\Auth;

class AuthControllerFactory
{
    public function __invoke($controllers)
    {
    	$services = $controllers->getServiceLocator();
    	$adapter = $services->get('Sqlite');

        return new AuthController($adapter);
    }
}
