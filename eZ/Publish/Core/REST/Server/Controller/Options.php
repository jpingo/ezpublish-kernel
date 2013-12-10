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

/**
 * Root controller
 */
class Options extends RestController
{
    /**
     * Lists the verbs available for a resource
     * @param $allowedMethods string comma separated list of supported methods. Depends on the matched OPTIONS route.
     * @return Values\Options
     */
    public function getRouteOptions( Request $_request, $allowedMethods )
    {
        $allowedMethods = explode( ',', $allowedMethods );
        $options = new Values\Options( $allowedMethods );

        // Coupling: should we instead trigger some kind of listener that adds whatever needs to be added to if required ?
        // Or is it fine to consider that this tiny responsibility is the controller's ?
        if ( $_request->attributes->has( 'corsAllowOrigin' ) )
        {
            $options->corsRequestMethods = $allowedMethods;
        }
    }
}
