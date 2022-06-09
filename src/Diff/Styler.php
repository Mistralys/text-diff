<?php
/**
 * File containing the {@see Styler} class.
 *
 * @package Diff
 * @subpackage Styler
 * @see Styler
 */

declare(strict_types=1);

namespace Mistralys\Diff\Styler;

use AppUtils\FileHelper;
use AppUtils\FileHelper_Exception;
use Mistralys\Diff\DiffException;

/**
 * Utility used to access the CSS styles needed to make the
 * HTML highlighting of the HTML diffs possible.
 *
 * @package Diff
 * @subpackage Styler
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
class Styler
{
    public const ERROR_CSS_FILE_NOT_FOUND = 66801;
    
    private string $path;
    private string $fileName = 'styles.css';

    /**
     * @throws DiffException
     */
    public function __construct()
    {
        $folder = sprintf(__DIR__.'/../../css/%s', $this->fileName);
        $path = realpath($folder);
        
        if($path === false)
        {
            throw new DiffException(
                'Could not find the highlight CSS file',
                sprintf(
                    'Tried looking in folder [%s].',
                    $folder
                ),
                self::ERROR_CSS_FILE_NOT_FOUND
            );
        }
        
        $this->path = $path;
    }

    /**
     * Retrieves the raw CSS source for the highlighting.
     *
     * @return string
     * @throws FileHelper_Exception
     */
    public function getCSS() : string
    {
        return FileHelper::readContents($this->path);
    }

    /**
     * Retrieves a fully formed `code` tag with the CSS,
     * to inject inline into an HTML document.
     *
     * @return string
     * @throws FileHelper_Exception
     */
    public function getStyleTag() : string
    {
        return sprintf(
            '<!-- Diff highlight CSS --><style>%s</style>',
            $this->getCSS()
        );
    }
    
   /**
    * Retrieves the path to the stylesheet file.
    * 
    * @return string
    */
    public function getStylesheetPath() : string
    {
        return $this->path;
    }
    
   /**
    * Retrieves the URL to the stylesheet file, given the
    * local URL to the application's vendor folder.
    *  
    * @param string $vendorURL The URL to the vendor folder (must be accessible in the webroot).
    * @return string
    */
    public function getStylesheetURL(string $vendorURL) : string
    {
        return sprintf(
            '%s/mistralys/text-diff/css/%s',
            rtrim($vendorURL, '/'),
            $this->fileName
        );
    }
    
    public function getStylesheetTag(string $vendorURL) : string
    {
        return sprintf(
            '<link rel="stylesheet" src="%s">',
            $this->getStylesheetURL($vendorURL)
        );
    }
}
