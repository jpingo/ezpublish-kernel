<?php
/**
 * File containing the CorsListener class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\EventListener;

use eZ\Bundle\EzPublishRestBundle\Cors\Manager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Handler for CORS (Cross-Origin Resource Sharing)
 *
 * Analyses the CORS headers, if provided, and either replies with an error, or adds the authorization headers.
 */
class CorsListener implements EventSubscriberInterface
{
    /**
     * @var Manager
     */
    protected $cors;

    public function __construct( Manager $cors )
    {
        $this->cors = $cors;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse'
        );
    }

    public function onKernelRequest( GetResponseEvent $event )
    {
        $request = $event->getRequest();

        if ( !$request->attributes->get( 'is_rest_request' ) )
        {
            return;
        }

        if ( !$request->headers->has( 'Origin' ) )
        {
            return;
        }

        // If the origin is allowed, we set the appropriate request attribute
        if ( $this->cors->hostIsAllowed( $request->headers->get( 'Origin' ) ) )
        {
            $request->attributes->set( 'corsAllowOrigin', $request->headers->get( 'Origin' ) );
        }
    }

    /**
     * Adds, if authorized, the CORS Headers to the response
     */
    public function onKernelResponse( FilterResponseEvent $event )
    {
        if ( !$event->getRequest()->attributes->get( 'is_rest_request' ) )
        {
            return;
        }

        if ( $event->getRequest()->attributes->has( 'corsAllowOrigin' ) )
        {
            $event->getResponse()->headers->set(
                'Access-Control-Allow-Origin',
                $event->getRequest()->attributes->get( 'corsAllowOrigin' )
            );
        }
    }
}
