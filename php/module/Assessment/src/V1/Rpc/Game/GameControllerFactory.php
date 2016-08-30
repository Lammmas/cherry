<?php
namespace Assessment\V1\Rpc\Game;

class GameControllerFactory
{
    public function __invoke($controllers)
    {
    	$services = $controllers->getServiceLocator();
    	$adapter = $services->get('Sqlite');
    	
        return new GameController($adapter);
    }
}
