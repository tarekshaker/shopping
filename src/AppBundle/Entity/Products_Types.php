<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products_Types
 *
 * @ORM\Table(name="products_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductsTypesRepository")
 */
class Products_Types implements \ArrayAccess
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
     * @ORM\Column(name="product_type", type="string", length=255)
     */
    private $product_type_name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getProductTypeName()
    {
        return $this->product_type_name;
    }

    /**
     * @param string $product_type_name
     */
    public function setProductTypeName($product_type_name)
    {
        $this->product_type_name = $product_type_name;
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

