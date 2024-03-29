<?php
/**
 * Webiny Platform (http://www.webiny.com/)
 *
 * @copyright Copyright (c) 2009-2014 Webiny LTD. (http://www.webiny.com/)
 * @license   http://www.webiny.com/platform/license
 */

namespace WebinyPlatform\Apps\Core\Components\PackageManager\Lib;

use Aws\Common\Facade\DataPipeline;
use Webiny\Component\StdLib\SingletonTrait;
use Webiny\Component\StdLib\StdLibTrait;
use WebinyPlatform\Apps\Core\Components\DevTools\Lib\DevToolsTrait;
use WebinyPlatform\Apps\Core\Components\PackageManager\EventHandlers\PackagesInitialized;

/**
 * PackageScanner scans the current installation and lists information about all installed apps and plugins.
 */
class PackageScanner
{
    use DevToolsTrait, SingletonTrait, StdLibTrait;

    const CACHE_KEY = 'Core.PackageManager.PackageScanner.Packages';

    /**
     * @var array An array containing a list of Package instances.
     */
    private $_packages = [];

    /**
     * Package scanner base constructor.
     */
    protected function _init()
    {
        // see if we have already all the packages, events and routes in cache
        $data = $this->_wCache()->read(self::CACHE_KEY);
        if ($data) {
            ##############
            # FROM CACHE #
            ##############

            // unpack cache
            $packageData = $this->unserialize($data);

            // register packages
            $this->_packages = $packageData['packages'];

            // register events
            $this->_wEvents()->setListeners($packageData['events']);

            // register routes
            $this->_wRouter()->setRoutes($packageData['routes']);

            unset($packageData);
        } else {
            ##################
            # NOT FROM CACHE #
            ##################

            // scan Apps folder
            $this->_packages['apps'] = $this->_scanApps('Public/Apps');

            // scan Plugins folder
            $this->_packages['plugins'] = $this->_scanPlugins('Public/Plugins');

            // scan Themes folder
            $this->_packages['themes'] = $this->_scanThemes('Public/Themes');

            // store the packages, events and routes in cache
           /* $packageData = [
                'packages'  => $this->_packages,
                'events'    => $this->_wEvents()->getListeners(),
                'routes'    => $this->_wRouter()->getRoutes()
            ];
            $this->_wCache()->save(self::CACHE_KEY, $packageData, (30 * 60));*/
            unset($packageData);
        }
    }

    static function clearCacheCallback($event)
    {
        //@TODO ovo mora ici u EventHandlers
        self::_wCache()->delete(self::CACHE_KEY);
    }

    public function listPackages()
    {
        return $this->_packages;
    }

    public function getPackage($package)
    {
        return $this->_packages[$package]; //check if isset
    }

    private function _scanApps($appRoot)
    {
        return $this->__scan($appRoot, "App");
    }

    private function _scanPlugins($pluginsRoot)
    {
        return $this->__scan($pluginsRoot, "Plugin");
    }

    private function _scanThemes($themesRoot)
    {
        return $this->__scan($themesRoot, "Theme");
    }

    private function __scan($root, $object)
    {
        $packages = $this->_wStorage()->readDir($root);
        $result = [];
        foreach ($packages as $package) {
            // parse packageinfo
            $info = $this->_wConfig()->parseConfig($package->getKey() . '/' . $object . '.yaml');

            // create package instance
            $class = '\\WebinyPlatform\\Apps\\Core\\Components\\PackageManager\\Lib\\' . $object;
            $result[$package->getKey()] = new $class($info, $package->getKey());
        }

        return $result;
    }
}