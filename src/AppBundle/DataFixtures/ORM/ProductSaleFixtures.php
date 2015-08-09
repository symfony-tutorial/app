<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProductSale;

class ProductSaleFixtures extends AbstractDataFixture
{

    protected function createAndPersistData() {
        $index = 0;
        foreach ($this->getReferences('product') as $product) {
            $index++;
            $productSale = new ProductSale();
            $productSale->setProduct($product)->setActive(rand(0, 1));
            $productSale->setPrice(rand(100, 10000));
            $randomDate = time() + rand(-100000, 100000);
            $startDate = new \DateTime();
            $startDate->setTimestamp($randomDate);
            $endDate = new \DateTime();
            $endDate->setTimestamp($randomDate + rand(100000, 1000000));
            $productSale->setStartDate($startDate);
            $productSale->setEndDate($endDate);
            $this->manager->persist($productSale);
            if($index % 500 ===0){
                $this->manager->flush();
                $this->manager->clear();
            }
        }
    }

    public function getOrder() {
        return 3;
    }

}
