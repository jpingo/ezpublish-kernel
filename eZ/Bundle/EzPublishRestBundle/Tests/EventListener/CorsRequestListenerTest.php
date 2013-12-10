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
class CorsRequestListenerTest extends CorsListenerTest
{
    /**
     * The corsAllowOrigin attribute is set in the request
     */
    public function testRequestKnownOrigin()
    {
        $origin = 'http://client.example.com';

        $this->request->headers->set( 'Origin', $origin );

        $this->getCorsManagerMock()
            ->expects( $this->once() )
            ->method( 'hostIsAllowed' )
            ->with( $origin )
            ->will( $this->returnValue( true ) );

        $this->getEventListener()->onKernelRequest( $this->getRequestEvent() );

        self::assertTrue( $this->request->attributes->has( 'corsAllowOrigin' ) );
    }

    /**
     * No CORS headers are sent back. Right ?
     */
    public function testUnknownOrigin()
    {
        $origin = 'http://client.example.com';

        $this->request->headers->set( 'Origin', $origin );

        $this->getCorsManagerMock()
            ->expects( $this->once() )
            ->method( 'hostIsAllowed' )
            ->with( $origin )
            ->will( $this->returnValue( false ) );

        $this->getEventListener()->onKernelRequest( $this->getRequestEvent() );

        self::assertFalse( $this->request->attributes->has( 'corsAllowOrigin' ) );
    }

    /**
     * No CORS headers are sent back
     */
    public function testNoOrigin()
    {
        $this->getEventListener()->onKernelRequest( $this->getRequestEvent() );

        self::assertFalse( $this->request->attributes->has( 'corsAllowOrigin' ) );
    }

    /**
     * @return GetResponseEvent|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRequestEvent()
    {
        return $this->getEventMock( 'Symfony\Component\HttpKernel\Event\GetResponseEvent' );
    }

    protected function getRequestMock()
    {
        return $this->request;
    }
}
