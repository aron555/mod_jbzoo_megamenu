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

$zoo = App::getInstance('zoo');

$categories = $modHelper->getCategories();

$attrs = array(
    'id' => $modHelper->getModuleId(),
    'class' => array(
        'yoo-zoo', // for Zoo widgets
        'jbzoo',
        'jbcategory-module',
        'jbcategory-module-bootstrap',
        (int)$params->get('category_display_border', 0) ? 'jbzoo-rborder' : '',
        'clearfix'
    ),
);
// главная страница каталога
$url = $zoo->route->frontpage($zoo->zoo->getApplication()->id);
//$this->_params->get('app_id');

$catIdsSup = [];
?>

<div class="solid-menus" xmlns="http://www.w3.org/1999/html">
    <div class="navbar navbar-hover p-0 shadow-none">

        <div class="dropdown dropdown-convey-width" data-animation="fadeIn">

            <a
                    class="dropdown-categories btn btn-danger dropdown-toggle"
                    href="<?= $url ?>"
                    data-toggle="dropdown"
                    data-title="Все категории">
                <i class="icon-home2 icn-left visible-sm-inline"></i>
                <span class="hidden-sm">Каталог</span>
            </a>

            <ul class="dropdown-menu">
                <li class="col-lg-12 px-0">
                    <div class="tabs side-tabs clearfix row">
                        <ul class="tab-nav clearfix tab-nav-hover col-md-auto col-12 p-0 list-unstyled">
                            <?php foreach ($categories as $category) : ?>
                                <li class="position-relative<?php echo $category['active_class']; ?>">
                                    <a
                                            href="<?= $category['cat_link'] ?>"
                                            data-tabs="s-tab-<?= $category['id'] ?>"
                                            class="prev-default pr-5"
                                            title="<?= $category['category_name'] ?>"
                                            data-toggle="collapse"
                                    >
                                        <span onclick="location.href='<?= $category["cat_link"] ?>'">
                                        <?= $category['category_name'] ?>
                                        </span>
                                        <span class="position-absolute caret"><i class="fas fa-angle-right"></i></span>

                                    </a>

                                </li>
                                <?php
                                $catIdsSup[$category['id']] = $category;

                                ?>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-container col-md col-12 hidden-xs overflow-auto">
                            <?php
                            foreach ($catIdsSup as $catId => $category) :
                                //echo "<pre>";var_dump($category['items_count']);echo "</pre>"; die();
                                if (!empty($category['childs'])) {
                                    $childs = $this->getChilds($category['id']);
                                    ?>
                                    <div class="s-tab-content clearfix" id="s-tab-<?= $category['id'] ?>">

                                        <div class="row">
                                            <?php
                                            foreach ($childs as $childId => $child) {
                                                $class = !empty($child['items']) ? "col-12 col-sm-6 col-md-4 col-xs-3" : "col-12 col-md-auto";

                                                ?>
                                                <div class="<?= $class ?>">
                                                    <div class="card mb-3">
                                                        <div class="card-header overflow-hidden bg-transparent border-success">
                                                            <h5 class="card-title text-center text-md-left text-uppercase">
                                                                <a class="p-0 h5"
                                                                   href="<?= $child['child_link'] ?>">
                                                                    <strong><?= $child['child_name'] ?></strong>
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <?php
                                                        if (!empty($child['items'])) {
                                                            ?>
                                                            <ul class="s-list p-0 list-unstyled list-group list-group-flush">
                                                                <?php

                                                                $layout = $params->get('item_layout', 'default');

                                                                foreach ($child['items'] as $itemId => $item) {

                                                                    $renderer = $modHelper->createRenderer('item');
                                                                    ?>
                                                                    <li class="list-group-item">
                                                                        <?php echo $renderer->render('item.' . $layout, array('item' => $item, 'params' => $params)); ?>
                                                                    </li>
                                                                    <?php

                                                                }
                                                                ?>

                                                            </ul>
                                                            <div class="card-footer text-muted">
                                                                <a href="<?= $child['child_link'] ?>">
                                                                        <span class="text-muted">
                                                                            Смотреть все
                                                                            <i class="fas fa-arrow-right"></i>
                                                                        </span>
                                                                </a>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>

                                                    </div><!--.card-->
                                                </div><!--.col--->
                                                <?php

                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            endforeach;
                            ?>
                            <hr>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
