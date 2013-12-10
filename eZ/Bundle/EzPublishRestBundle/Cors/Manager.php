<?php
/**
 * File containing the Manager class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Cors;

class Manager
{
    public function hostIsAllowed( $host )
    {
        return true;
    }
}
