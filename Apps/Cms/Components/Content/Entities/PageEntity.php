<?php
/**
 * Webiny Platform (http://www.webiny.com/)
 *
 * @copyright Copyright (c) 2009-2014 Webiny LTD. (http://www.webiny.com/)
 * @license   http://www.webiny.com/platform/license
 */

namespace WebinyPlatform\Apps\Cms\Components\Content\Entities;

use WebinyPlatform\Apps\Cms\Components\Content\Entities\Generated\PageEntity as GeneratedPageEntity;

class PageEntity extends GeneratedPageEntity
{

    /**
     * This is the class you can change as you see fit.
     * Add new methods, override existing ones, add new attributes, whatever you need.
     *
     * If you need to use EntityBuilder at some point, feel free to do so, as we will only overwrite the parent class
     * created by code generator and leave all your code in this class intact.
     *
     * Happy coding! :)
     */

    protected function _entityStructure() {
        parent::_entityStructure();
        // One2Many
        $this->attr('comments')->one2many('page')->setEntity('Cms.Content.CommentEntity');

        // Many2Many
        $this->attr('labels')->many2many('Label2Page')->setEntity('Cms.Content.LabelEntity');
    }
}