<?php
/**
 * File containing the InlineFragmentRenderer class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishCoreBundle\Fragment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer as BaseRenderer;

class InlineFragmentRenderer extends BaseRenderer
{
    /**
     * @var FragmentUriGenerator
     */
    private $fragmentUriGenerator;

    protected function generateFragmentUri( ControllerReference $reference, Request $request, $absolute = false )
    {
        if ( !isset( $this->fragmentUriGenerator ) )
        {
            $this->fragmentUriGenerator = new FragmentUriGenerator;
        }

        // Generate base fragment URI and add other needed attributes
        $this->fragmentUriGenerator->generateFragmentUri( $reference, $request, $absolute );
        if ( $request->attributes->has( 'semanticPathinfo' ) )
            $reference->attributes['semanticPathinfo'] = $request->attributes->get( 'semanticPathinfo' );
        if ( $request->attributes->has( 'viewParametersString' ) )
            $reference->attributes['viewParametersString'] = $request->attributes->get( 'viewParametersString' );

        return parent::generateFragmentUri( $reference, $request, $absolute );
    }
}
