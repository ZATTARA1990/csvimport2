<?php

namespace CSVParceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="CSVParceBundle\Repository\ProductRepository")
 */
class Product
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")

     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $productName;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     *
     *  @Assert\NotBlank()
     *
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     *
     *  @Assert\NotBlank()
     *
     */
    private $stock;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     *  @Assert\NotBlank()
     *
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="discontinued", type="smallint")
     *
     *  @Assert\NotBlank()
     *
     */
    private $discontinued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="discontinued_date", type="datetime")
     */
    private $discontinuedDate;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productName
     *
     * @param string $productName
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string 
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set discontinued
     *
     * @param integer $discontinued
     * @return Product
     */
   public function setDiscontinued($discontinued)
    {
        $this->discontinued = $discontinued;

        return $this;
    }

    /**
     * Get discontinued
     *
     * @return integer 
     */
   public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * Set discontinuedDate
     *
     * @param \DateTime $discontinuedDate
     * @return Product
     */
    public function setDiscontinuedDate($discontinuedDate=null)
    {
        $this->discontinuedDate = $discontinuedDate;

        return $this;
    }

    /**
     * Get discontinuedDate
     *
     * @return \DateTime 
     */
    public function getDiscontinuedDate()
    {
        return $this->discontinuedDate;
    }
}
