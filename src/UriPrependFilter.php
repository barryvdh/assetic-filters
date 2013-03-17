<?php namespace Assetic\Filter;

use Assetic\Asset\AssetInterface;

/**
 * Prepends paths in CSS assets through Minify_CSS_UriRewriter.
 *
 * @link https://github.com/mrclay/minify/blob/master/min/lib/Minify/CSS/UriRewriter.php
 * @author Barry vd. Heuvel <barry@fruitcakestudio.nl>
 */
class UriPrependFilter implements FilterInterface {

    protected $prepend;

    public function __construct($prepend = null)
    {
        $this->prepend = $prepend;
    }

    public function setPrepend($path)
    {
        $this->prepend = $path;
    }

    public function filterLoad(AssetInterface $asset){}

    public function filterDump(AssetInterface $asset)
    {
        $asset->setContent(\Minify_CSS_UriRewriter::prepend($asset->getContent(), $this->prepend));
    }

}
