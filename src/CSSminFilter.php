<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2013 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assetic\Filter;

use Assetic\Asset\AssetInterface;

/**
 * Filters assets through CSSmin.
 *
 * @link https://github.com/mrclay/minify/blob/master/min/lib/CSSmin.php
 * @author Barry vd. Heuvel <barry@fruitcakestudio.nl>
 */
class CSSminFilter implements FilterInterface
{

    public function filterLoad(AssetInterface $asset){}

    public function filterDump(AssetInterface $asset)
    {
        $cssmin = new \CSSmin();
        $asset->setContent($cssmin->run($asset->getContent()));
    }
}
