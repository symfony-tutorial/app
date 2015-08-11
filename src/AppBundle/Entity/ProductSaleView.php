<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductSale
 *
 * @ORM\Table(name="product_sale_view")
 * @ORM\Entity(readOnly=true)
 */
class ProductSaleView
{
    const REPOSITORY = 'AppBundle:ProductSaleView';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoryId", type="integer", nullable=true)
     */
    private $categoryId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="category", type="integer", nullable=true)
     */
    private $category;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;
    
    function getId() {
        return $this->id;
    }

    function getCode() {
        return $this->code;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getPrice() {
        return $this->price;
    }

    function getCategoryId() {
        return $this->categoryId;
    }

    function getCategory() {
        return $this->category;
    }

    function getStock() {
        return $this->stock;
    }

}
