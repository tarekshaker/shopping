<?php
/**
 * Created by PhpStorm.
 * User: Teka
 * Date: 9/1/2018
 * Time: 2:41 AM
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Products_Types;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductsRepository")
 * @ORM\Table(name="products")
 */
class Products implements \ArrayAccess
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $product_name;
    /**
     * @ORM\Column(type="string")
     */
        private $product_desc;
    /**
     * @ORM\Column(type="integer")
     */
    private $price;
    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $image = null;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Products_Types")
     * @ORM\JoinColumn(name="product_type", referencedColumnName="id")
     */
    private $product_type;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sale_amount = null;


    /** @ORM\OneToMany(targetEntity="AppBundle\Entity\Cart_items", mappedBy="product_id", orphanRemoval=true) */
    private $cartProducts;

    public function __construct()
    {
        $this->cartProducts =  new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCartProducts()
    {
        return $this->cartProducts;
    }

    /**
     * @param mixed $cartProducts
     */
    public function setCartProducts($cartProducts)
    {
        $this->cartProducts = $cartProducts;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * @param mixed $product_name
     */
    public function setProductName($product_name)
    {
        $this->product_name = $product_name;
    }

    /**
     * @return mixed
     */
    public function getProductDesc()
    {
        return $this->product_desc;
    }

    /**
     * @param mixed $product_desc
     */
    public function setProductDesc($product_desc)
    {
        $this->product_desc = $product_desc;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->product_type;
    }

    /**
     * @param mixed $product_type
     */
    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
    }



    /**
     * @return mixed
     */
    public function getSaleAmount()
    {
        return $this->sale_amount;
    }

    /**
     * @param mixed $sale_amount
     */
    public function setSaleAmount($sale_amount)
    {
        $this->sale_amount = $sale_amount;
    }

    //implementation of Array Access Methods
    public function offsetExists($offset) {
        return isset($this->$offset);
    }

    public function offsetSet($offset, $value) {
        $this->$offset = $value;
    }

    public function offsetGet($offset) {
        return $this->$offset;
    }

    public function offsetUnset($offset) {
        $this->$offset = null;
    }



}