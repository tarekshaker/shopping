<?php
/**
 * Created by PhpStorm.
 * User: Teka
 * Date: 9/1/2018
 * Time: 2:41 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartItemsRepository")
 * @ORM\Table(name="cart_items" ,indexes={@ORM\Index(name="cart_id_idx", columns={"cart_id"})})
 */
class Cart_items implements \ArrayAccess
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int|null
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Products", cascade={"persist"},inversedBy="cartProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $product_id;

    /**
     * @var int|null
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Carts",  cascade={"persist"},inversedBy="cart")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id", nullable=false,onDelete="CASCADE")
     */

    private $cart_id;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $is_wishlisted;


    /**
     *  @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updated_at;


    public function __construct()
    {
        $this->updated_at = new DateTime();
    }


    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * Get cart_id
     *
     * @return \AppBundle\Entity\Carts
     */
    public function getCartId()
    {
        return $this->cart_id;
    }

    /**
     * @return mixed
     */
    public function getisWishlisted()
    {
        return $this->is_wishlisted;
    }

    /**
     * @param mixed $is_wishlisted
     */
    public function setIsWishlisted($is_wishlisted)
    {
        $this->is_wishlisted = $is_wishlisted;
    }

    /**
     * Set $cart_id
     *
     * @param \AppBundle\Entity\Carts $cart_id
     *
     * @return cart_items
     */
    public function setCartId($cart_id)
    {
        $this->cart_id = $cart_id;
    }


    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $quantity;


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
     * Get product_id
     * @return \AppBundle\Entity\Products
     */
    public function getProductId()
    {
        return $this->product_id;
    }


    /**
     * Set product_id
     *
     * @param \AppBundle\Entity\Products $product_id
     *
     * @return cart_items
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
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