<?php namespace Assetic\Filter;

use Assetic\Asset\AssetInterface;

/**
 * UriRewriteFilter is a rewrite and port of the popular CssUriRewrite class written by Steve Clay.
 * Original source can be found by following the links below.
 *
 * This filter is based on the UriRewriteFilter from jasonlewis' Basset:
 * https://github.com/jasonlewis/basset/blob/master/src/Basset/Filter/UriRewriteFilter.php
 *
 * @author    Steve Clay
 * @link      <https://github.com/mrclay/minify>
 * @license   <https://github.com/mrclay/minify/blob/master/LICENSE.txt>
 * @package   Minify
 * @copyright 2008 Steve Clay / Ryan Grove
 */
class UriPrependFilter implements FilterInterface {

    /**
     * Path to prepend to relative URIs
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new UriPrependFilter instance.
     *
     * @param  string  $path    The path to prepend
     * @return void
     */
    public function __construct($path = null)
    {
        $this->path = $path;
    }

    /**
     * Set the prepend path.
     *
     * @param  string  $path    The path to prepend
     * @return void
     */
    public function setPrepend($path)
    {
        $this->path = $path;
    }

    /**
     * Apply filter on file load.
     *
     * @param  Assetic\Asset\AssetInterface  $asset
     * @return void
     */
    public function filterLoad(AssetInterface $asset){}

    /**
     * Apply a filter on file dump.
     *
     * @param  Assetic\Asset\AssetInterface  $asset
     * @return void
     */
    public function filterDump(AssetInterface $asset)
    {

        $content = $asset->getContent();

        $content = $this->trimUrls($content);

        $content = preg_replace_callback('/@import\\s+([\'"])(.*?)[\'"]/', array($this, 'processUriCallback'), $content);

        $content = preg_replace_callback('/url\\(\\s*([^\\)\\s]+)\\s*\\)/', array($this, 'processUriCallback'), $content);

        $asset->setContent($content);
    }

    /**
     * Takes a path and transforms it to a real path.
     *
     * @param  string  $path
     * @return string
     */
    protected function realPath($path)
    {
        if ($realPath = realpath($path))
        {
            $path = $realPath;
        }

        return rtrim($path, '/\\');
    }

    /**
     * Trims URLs.
     *
     * @param  string  $content
     * @return string
     */
    protected function trimUrls($content)
    {
        return preg_replace('/url\\(\\s*([^\\)]+?)\\s*\\)/x', 'url($1)', $content);
    }

    /**
     * Processes a regular expression callback, determines the URI and returns the rewritten URIs.
     *
     * @param  array  $matches
     * @return string
     */
    protected function processUriCallback($matches)
    {
        $isImport = $matches[0][0] === '@';

        // Determine what the quote character and the URI is, if there is one.
        $quoteCharacter = $uri = null;

        if ($isImport)
        {
            $quoteCharater = $matches[1];

            $uri = $matches[2];
        }
        else
        {
            if ($matches[1][0] === "'" or $matches[1][0] === '"')
            {
                $quoteCharacter = $matches[1][0];
            }

            if ( ! $quoteCharacter)
            {
                $uri = $matches[1];
            }
            else
            {
                $uri = substr($matches[1], 1, strlen($matches[1]) - 2);
            }
        }

        if($this->path){
            $uri = $this->path . $uri;
        }

        if ($isImport)
        {
            return "@import {$quoteCharacter}{$uri}{$quoteCharacter}";
        }

        return "url({$quoteCharacter}{$uri}{$quoteCharacter})";
    }


    /**
     * Removes dots from a URI.
     *
     * @param  string  $uri
     * @return string
     */
    protected function removeDots($uri)
    {
        $uri = str_replace('/./', '/', $uri);

        do
        {
            $uri = preg_replace('@/[^/]+/\\.\\./@', '/', $uri, 1, $changed);
        }
        while ($changed);

        return $uri;
    }

}
