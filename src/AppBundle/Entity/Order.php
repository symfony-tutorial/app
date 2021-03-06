<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="`order`", indexes={@ORM\Index(name="fk_order_customer_idx", columns={"customer_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Order
{

    const REPOSITORY = 'AppBundle:Order';
    const STATUS_NEW = 1;
    const STATUS_PROCESSING = 10;
    const STATUS_PROCESSING_PRODUCTS_MISSING = 11;
    const STATUS_PROCESSING_PRODUCTS_RESERVED = 14;
    const STATUS_PROCESSING_PACKAGING = 15;
    const STATUS_DELIVERY_STARTED = 20;
    const STATUS_DELIVERED = 29;
    const STATUS_CANCELLED = 30;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @var \Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    private $customer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OrderProductLine", mappedBy="order")
     */
    private $productLines;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping_address_id", referencedColumnName="id")
     * })
     */
    private $shippingAddress;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     * })
     */
    private $billingAddress;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Order
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Order
     */
    public function setCreateDate($createDate) {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate() {
        return $this->createDate;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     * @return Order
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null) {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer 
     */
    public function getCustomer() {
        return $this->customer;
    }

    public function getProductLines() {
        return $this->productLines;
    }

    public function setProductLines(array $productLines) {
        $this->productLines = $productLines;
        return $this;
    }

    public function addProductLine(OrderProductLine $productLine) {
        $productLine->setOrder($this);
        $this->productLines[] = $productLine;
        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return \AppBundle\Entity\Address 
     */
    public function getShippingAddress() {
        return $this->shippingAddress;
    }

    /**
     * Get billingAddress
     *
     * @return \AppBundle\Entity\Address 
     */
    public function getBillingAddress() {
        return $this->billingAddress;
    }

    public function setShippingAddress(Address $shippingAddress) {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function setBillingAddress(Address $billingAddress) {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public function __toString() {
        return (string) $this->getId();
    }

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        if (!$this->id) {
            $this->status = self::STATUS_NEW;
        }
    }

}
