<?php
/**
 * File containing the CorsListenerTest class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Tests\EventListener;

use eZ\Bundle\EzPublishRestBundle\Cors\Manager as CorsManager;
use eZ\Bundle\EzPublishRestBundle\EventListener\CorsListener;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @covers eZ\Bundle\EzPublishRestBundle\EventListener\CorsListener
 */
abstract class CorsListenerTest extends EventListenerTest
{
    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    public function setUp()
    {
        $this->request = new Request;
        $this->request->attributes->set( 'is_rest_request', true );
    }

    /**
     * @return CorsListener
     */
    protected function getEventListener()
    {
        return new CorsListener(
            $this->getCorsManagerMock()
        );
    }

    /**
     * @return CorsManager|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCorsManagerMock()
    {
        if ( !isset( $this->corsManagerMock ) )
        {
            $this->corsManagerMock = $this->getMock( 'eZ\Bundle\EzPublishRestBundle\Cors\Manager' );
        }
        return $this->corsManagerMock;
    }

    /**
     * Returns an array with the events the listener should be subscribed to
     */
    public function provideExpectedSubscribedEventTypes()
    {
        return array(
            array( array( KernelEvents::REQUEST, KernelEvents::RESPONSE ) )
        );
    }

    /**
     * @return GetResponseEvent|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRequestEvent()
    {
        return $this->getEventMock( 'Symfony\Component\HttpKernel\Event\GetResponseEvent' );
    }

    /**
     * @return FilterResponseEvent|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getResponseEvent()
    {
        $event = $this->getEventMock( 'Symfony\Component\HttpKernel\Event\GetResponseEvent' );

        $event->expects( $this->any() )
            ->method( 'getResponse' )
            ->will( $this->returnValue( $this->response ) );
    }

    protected function getRequestMock()
    {
        return $this->request;
    }
}
