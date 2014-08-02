<?php

namespace Zoop\User\Service;

use Zoop\GatewayModule\HttpResolver;
use Zoop\User\HttpAdapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class HttpAdapterServiceFactory implements FactoryInterface
{
    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $return = new HttpAdapter(
            [
                'realm' => 'zoop',
                'accept_schemes' => 'basic'
            ]
        );
        
        $resolver = new HttpResolver(
            $serviceLocator->get(
                $serviceLocator
                    ->get('config')['zoop']['gateway']['authentication_service_options']['per_session_adapter']
            )
        );
        
        $return->setRequest($serviceLocator->get('request'));
        $return->setResponse($serviceLocator->get('response'));
        $return->setBasicResolver($resolver);

        return $return;
    }
}
