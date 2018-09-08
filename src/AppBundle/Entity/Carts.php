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
use AppBundle\Entity\Products;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartsRepository")
 * @ORM\Table(name="carts")
 */
class Carts
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $total_price;

    /** @ORM\OneToMany(targetEntity="AppBundle\Entity\Cart_items", mappedBy="cart_id", orphanRemoval=true) */
    private $cart;

    /**
     * @ORM\Column(type="string")
     */
    private $session_id;

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * @param mixed $session_id
     */
    public function setSessionId($session_id)
    {
        $this->session_id = $session_id;
    }



    public function __construct()
    {
        $this->cart =  new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param mixed $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
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
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    /**
     * @param mixed $total_price
     */
    public function setTotalPrice($total_price)
    {
        $this->total_price = $total_price;
    }



}