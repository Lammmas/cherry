<?php
namespace Assessment\V1\Rpc\Frontend;

class FrontendControllerFactory
{
    public function __invoke($controllers)
    {
        return new FrontendController();
    }
}
