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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @covers eZ\Bundle\EzPublishRestBundle\EventListener\CorsListener
 */
class CorsResponseListenerTest extends CorsListenerTest
{
    /** @var Response */
    protected $response;

    public function setUp()
    {
        parent::setUp();
        $this->response = new Response();
    }

    public function testNotCorsRequest()
    {
        $this->getEventListener()->onKernelResponse( $this->getResponseEvent() );

        self::assertFalse(
            $this->response->headers->has( 'Access-Control-Allow-Origin' )
        );
    }

    public function testCorsRequest()
    {
        $origin = 'http://client.example.com';
        $this->request->attributes->set( 'corsAllowOrigin', $origin );

        $this->getEventListener()->onKernelResponse( $this->getResponseEvent() );

        self::assertTrue(
            $this->response->headers->has( 'Access-Control-Allow-Origin' )
        );
        self::assertEquals( $origin, $this->response->headers->get( 'Access-Control-Allow-Origin' ) );
    }

    /**
     * @return FilterResponseEvent|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getResponseEvent()
    {
        $event = $this->getEventMock( 'Symfony\Component\HttpKernel\Event\FilterResponseEvent' );

        $event->expects( $this->any() )
            ->method( 'getResponse' )
            ->will( $this->returnValue( $this->response ) );

        return $event;
    }

    protected function getRequestMock()
    {
        return $this->request;
    }
}
