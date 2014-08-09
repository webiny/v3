<?php
/**
 * Webiny Platform (http://www.webiny.com/)
 *
 * @copyright Copyright (c) 2009-2014 Webiny LTD. (http://www.webiny.com/)
 * @license   http://www.webiny.com/platform/license
 */
use Webiny\Component\ClassLoader\ClassLoader;
use Webiny\Component\StdLib\StdObject\DateTimeObject\DateTimeObject;
use WebinyPlatform\Apps\Cms\Components\Entities\DateTesterEntity;
use WebinyPlatform\Apps\Core\Components\Bootstrap\Lib\Bootstrap;
use Webiny\Component\Mongo\Mongo;
use WebinyPlatform\Apps\Core\Components\DevTools\Lib\Entity\Entity;
use WebinyPlatform\Apps\Developer\Components\CodeGenerator\Lib\CodeGenerator;

/**
 * Get absolute path to app root.
 */
$absPath = realpath(dirname(__FILE__) . '/../../../') . '/';

/**
 * Register default autoloader before we can do anything else.
 */
require $absPath . 'Vendors/Webiny/Component/ClassLoader/ClassLoader.php';
ClassLoader::getInstance()->registerMap([
                                            'WebinyPlatform' => $absPath . 'Public',
                                            'Webiny'         => $absPath . 'Vendors/Webiny'
                                        ]
);

/**
 * Initialize the bootstrap
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);
Bootstrap::getInstance();

Mongo::setConfig(realpath(__DIR__) . '/../Entity/MongoExampleConfig.yaml');
Entity::setConfig(realpath(__DIR__) . '/../Entity/EntityExampleConfig.yaml');

$entityStructure = json_decode(file_get_contents('./Json/DateEntityStructure.json'), true);
CodeGenerator::getInstance()->generateEntityClass($entityStructure);

$dateTester = new DateTesterEntity();

$date = new DateTimeObject('now');

$data = [
    'unix'     => $date,
    'time'     => $date
];

$dateTester->populate($data)->save();

/*foreach ($dateTester->getAttributes() as $key => $attr) {
    echo $key . ': ' . $attr->value() . "<br>";
}*/

$dateTesters = DateTesterEntity::find();
foreach($dateTesters as $dt){
    print_r($dt->toArray());
}