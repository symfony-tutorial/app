<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;

class CategoryFixtures extends AbstractDataFixture
{

    private $categoriesCount = 0;
    private $categories = array(
        'computers' => array('servers', 'desktop', 'laptops', 'components', 'periferals'),
        'phones and tablets' => array('phones', 'tablets', 'accessories'),
        'appliances' => array('coffe machines', 'washing machines', 'blenders', 'juicers', 'fridges', 'air conditionner'),
        'video games' => array('consoles', 'games', 'accessories'),
        'televisions' => array('smart TV', 'curved TV', '3D TV', 'projectors'),
        'video' => array('video cameras', 'cam corder', 'mini cameras'),
        'books' => array('textbooks', 'magazines', 'e-books', 'audible books'),
        'sports' => array('tennis', 'footbal', 'fitness', 'athletic clothing', 'golf'),
        'outdoor' => array('hunting', 'fishing', 'hiking', 'biking'),
        'alcoholic drinks' => array('beer', 'wine', 'string alcohols'),
        'water' => array('sparkling', 'non sparkling'),
        'tea' => array(),
        'juice' => array(),
        'milk products' => array('milk', 'cheese', 'cream'),
        'canned food' => array('fish', 'chiken', 'salads', 'prepared food'),
        'coffee' => array('instant', 'expresso', 'filter'),
        'sweets' => array('chocolate', 'biscuits', 'candies'),
        'clothing' => array('for men', 'for wemen', 'for kids', 'casual', 'sport', 'fancy', 'for winter', 'for summer'),
        'natural products' => array('cosmetics', 'hygiene', 'medicinal plants'),
        'watches' => array('for men', 'for wemen', 'for kids', 'casual', 'sport', 'fancy'),
        'jewelry' => array()
    );

    protected function createAndPersistData() {
        foreach ($this->categories as $parent => $children) {
            $parentCategory = $this->createAndPersistCategory($parent);
            foreach ($children as $label) {
                $this->createAndPersistCategory($label, $parentCategory);
            }
        }
    }

    private function createAndPersistCategory($label, $parentCategory = null) {
        $this->categoriesCount ++;
        $category = new Category();
        $category->setLabel($label)->setParentCategory($parentCategory);
        $this->manager->persist($category);

        $this->setReference(sprintf('category_%s', $this->categoriesCount), $category);

        return $category;
    }

    public function getOrder() {
        return 1;
    }

}
