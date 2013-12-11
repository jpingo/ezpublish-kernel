<?php
/**
 * File containing the Root controller class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\REST\Server\Controller;

use eZ\Publish\Core\REST\Server\Values;
use eZ\Publish\Core\REST\Server\Controller as RestController;
use Symfony\Component\HttpFoundation\Request;
use eZ\Bundle\EzPublishRestBundle\Cors\Manager as CorsManager;

/**
 * Root controller
 */
class Options extends RestController
{
    /**
     * @var CorsManager
     */
    protected $corsManager;

    public function __construct( CorsManager $corsManager )
    {
        $this->corsManager = $corsManager;
    }

    /**
     * Lists the verbs available for a resource
     * @param $allowedMethods string comma separated list of supported methods. Depends on the matched OPTIONS route.
     * @return Values\Options
     */
    public function getRouteOptions( $allowedMethods )
    {
        return new Values\Options(
            explode( ',', $allowedMethods )
        );
    }
}
