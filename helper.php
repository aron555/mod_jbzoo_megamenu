<?php
/**
 * JBZoo Application
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Application
 * @license    GPL-2.0
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/JBZoo
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . '/components/com_zoo/config.php';
require_once JPATH_BASE . '/media/zoo/applications/jbuniversal/framework/jbzoo.php';
require_once JPATH_BASE . '/media/zoo/applications/jbuniversal/framework/classes/jbmodulehelper.php'; // TODO move to bootstrap

use Joomla\CMS\Factory;

/**
 * Class JBModuleHelperMegamenu
 */
class JBModuleHelperMegamenu extends JBModuleHelper
{
    /**
     * @param JRegistry $params
     * @param stdClass $module
     */
    public function __construct(JRegistry $params, $module)
    {
        parent::__construct($params, $module);

        $this->_initParams();

        $document = Factory::getDocument();
        $base = JUri::base();
        $document->addStyleSheet($base . 'modules/' . $module->module . '/assets/styles.css');
        $document->addScript($base . 'modules/' . $module->module . '/assets/script.js');
    }

    /**
     * @return array|null
     */
    public function getCategories()
    {
        $renderCat = array();
        $appId = (int)$this->_params->get('application', false);
        //$menuItem   = (int)$this->_params->get('menu_item', 0);
        $categories = $this->_getCategories();
        $curCatId = $this->getCurrentCategory();

        if ($appId && !empty($categories)) { //


            foreach ($categories as $category) {

                $childs = $this->_getNestedCat($category->id);

                $catUrl = $this->app->route->category($category);

                $currentCat = array(
                    'id' => $category->id,
                    'active_class' => ($curCatId == $category->id) ? 'ui-tabs-active' : '',
                    'cat_link' => $catUrl,
                    'category_name' => $category->name,
                    'child_count' => count($childs),
                    'childs' => array(),
                    'level' => '1',
                    'items' => array(),
                );

                if ((int)$this->_params->get('display_items', 1)) {
                    $currentCat['items'] = $this->_getItems($category->id);
                    $currentCat['items_count'] = count((array)$this->_getItems($category->id));
                }


                if (!empty($this->_getNestedCat($category->id))) {
                    $currentCat['childs'] = $this->_getNestedCat($category->id);
                }

                $renderCat[$category->id] = $currentCat;
            }
        }

        return $renderCat;
    }

    /**
     * @param array $ids
     * @param string $order
     * @return array|null
     */
    public function getChilds($id)
    {

        $renderChild = array();
        $curCatId = $this->getCurrentCategory();

        $childs = $this->_getNestedCat($id);

        foreach ($childs as $child) {
            $childUrl = $this->app->route->category($child);

            $currentCat = array(
                'id' => $child->id,
                'active_class' => ($curCatId == $child->id) ? ' ui-tabs-active' : '',
                'child_link' => $childUrl,
                'child_name' => $child->name,
                'childs' => array(),
                'level' => '1',
                'items' => array(),
            );

            if ((int)$this->_params->get('display_items', 1)) {
                $currentCat['items'] = $this->_getItems($child->id);
                $currentCat['items_count'] = count((array)$this->_getItems($child->id));
            }


            if (!empty($this->_getNestedCat($child->id))) {
                $currentCat['childs'] = $this->_getNestedCat($child->id);
            }

            $renderChild[$child->id] = $currentCat;

        }


        //echo "<pre>";var_dump($child->id);echo "</pre>";

        return $renderChild;
    }


    /**
     * Get category list
     * @return array
     */
    protected function _getCategories()
    {
        $categories = JBModelCategory::model()->getList(
            $this->_params->get('app_id'),
            array(
                'limit' => $this->_params->get('category_limit'),
                'parent' => $this->_params->get('cat_id'),
                'order' => $this->_params->get('category_order'),
                'published' => 1,
            )
        );
        return $categories;
    }

    protected function _getNestedCat($catId)
    {
        $categories = JBModelCategory::model()->getList(
            $this->_params->get('app_id'),
            array(
                'limit' => $this->_params->get('category_limit'),
                'parent' => $catId,
                'order' => $this->_params->get('category_order'),
                'published' => 1,
            )
        );
        return $categories;
    }

    /*protected function _getChilds($categoryId)
    {
        $childsIds = JBModelCategory::model()->getNestedCategories(
            $categoryId,
            $this->_params->get('app_id')
        );

        return $childsIds;
    }*/

    /**
     * Get items
     * @param $catId
     * @return mixed
     */
    protected function _getItems($catId)
    {
        $items = JBModelItem::model()->getList(
            $this->_params->get('app_id'),
            $catId,
            $this->_params->get('type_id', false),
            array(
                'limit' => $this->_params->get('items_limit'),
                'published' => "1",
                'order' => $this->_params->get('item_order'),
                'category_nested' => true, // выбирать из вложенный категорий
            )
        );

        return (array)$items;
    }


    /**
     * Set mixed params for module
     */
    protected function _initParams()
    {
        list($appId, $catId) = explode(':', $this->_params->get('application', '0:0'));
        $itemsLimit = (int)$this->_params->get('items_limit', 4);
        $categoryLimit = (int)$this->_params->get('category_limit', 0);

        ($itemsLimit == 0) ? $this->_params->set('items_limit', null) : $this->_params->set('items_limit', $itemsLimit);
        ($categoryLimit == 0) ? $this->_params->set('category_limit', null) : $this->_params->set('category_limit', $categoryLimit);

        $this->_params->set('app_id', (int)$appId);
        $this->_params->set('cat_id', (int)$catId);

    }

    /**
     * @return int
     */
    public function getCurrentCategory()
    {
        return $this->app->jbrequest->getSystem('category', 0);
    }
}