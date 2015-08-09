<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProductStock;

class ProductStockFixtures extends AbstractDataFixture
{

    protected function createAndPersistData() {
        $index = 0;
        foreach ($this->getReferences('product') as $product) {
            $index++;
            $productStock = new ProductStock();
            $productStock->setProduct($product)->setQuantity(rand(0, 1000));
            $warehouse = $this->getReference(sprintf('warehouse_%s', rand(1, 100)));
            $productStock->setWarehouse($warehouse);
            $this->manager->persist($productStock);
            if ($index % 500 === 0) {
                $this->manager->flush();
                $this->manager->clear();
            }
        }
    }

    public function getOrder() {
        return 3;
    }

}
