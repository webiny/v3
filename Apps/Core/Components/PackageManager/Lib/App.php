<?php
/**
 * Webiny Platform (http://www.webiny.com/)
 *
 * @copyright Copyright (c) 2009-2014 Webiny LTD. (http://www.webiny.com/)
 * @license   http://www.webiny.com/platform/license
 */

namespace WebinyPlatform\Apps\Core\Components\PackageManager\Lib;

use Webiny\Component\Config\ConfigObject;
use WebinyPlatform\Apps\Core\Components\DevTools\Lib\DevToolsTrait;

/**
 * Class that holds information about an application.
 */
class App extends PackageAbstract
{
    use DevToolsTrait;

    /**
     * Application base constructor.
     *
     * @param ConfigObject $info Application information object.
     * @param string       $path Absolute path to the application.
     */
    public function __construct(ConfigObject $info, $path)
    {
        parent::__construct($info, $path, "app");
        $this->_scanComponents();
    }

    /**
     * Returns a list of application components.
     *
     * @return array An array containing instances of Component.
     */
    public function getComponents()
    {
        return $this->_components;
    }

    /**
     * This private function scans components of the current app.
     *
     * @return array An array containing instances of Component.
     */
    private function _scanComponents()
    {
        $componentDir = $this->_wStorage()->readDir($this->getPath().'/Components');
        foreach ($componentDir as $c) {
            // parse component info

            $info = $this->_wConfig()->parseConfig($c->getKey() . '/Component.yaml');

            // create component instance
            $this->_components[$c->getKey()] = new Component($info, $c->getKey());
        
        }
    }

}