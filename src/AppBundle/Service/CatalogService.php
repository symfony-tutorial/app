<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use AppBundle\Entity\ProductSale;
use AppBundle\Entity\ProductStock;
use Doctrine\ORM\Query\Expr\Join;

class CatalogService extends AbstractDoctrineAware
{

    const ID = 'app.catalog';
    
    public function getCategories()
    {
        return $this->entityManager
        ->createQueryBuilder()
        ->select('category.id, category.label, parent.id as parent_id, parent.label as parent_label')
        ->from(Category::REPOSITORY, 'category')
        ->leftJoin('category.parentCategory', 'parent')
        ->getQuery()
        ->getResult();
    }

    public function getProductSales() {
    return $this->entityManager
        ->createQueryBuilder()
        ->select('product.id, product.code, product.title, product.description, '
                . 'productSale.price, category.label as categoryName, '
                . 'SUM(productStock.quantity) as stock')
        ->from(ProductSale::REPOSITORY, 'productSale')
        ->innerJoin('productSale.product', 'product')
        ->leftJoin('product.category', 'category')
        ->leftJoin(
                ProductStock::REPOSITORY, 'productStock',
          Join::WITH, 'product = productStock.product'
        )
        ->where('productSale.active = true')
        ->andWhere('CURRENT_DATE() BETWEEN productSale.startDate AND productSale.endDate')
        ->groupBy('productSale.id')
        ->getQuery()
        ->getResult();
}
}
