<?php
/**
 * Created by PhpStorm.
 * User: Teka
 * Date: 9/1/2018
 * Time: 2:57 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CartItemsRepository extends EntityRepository
{

    public function findAllProductsInCart($cart)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('c')
            ->from('AppBundle:Cart_items', 'c')
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 0')
            ->orderBy('c.id','ASC')
            ->setParameter('cart', $cart);

        return $qb->getQuery()->getResult();
    }


    public function isItemsExisted($productId, $cart)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('c')
            ->from('AppBundle:Cart_items', 'c')
            ->andWhere('c.product_id = :productId')
            ->andWhere('c.cart_id = :cart')
            ->setParameter('productId', $productId)
            ->setParameter('cart', $cart);

        $itemExisted = $qb->getQuery()->getResult();

        if (empty($itemExisted)) {
            return FALSE;
        } else {
            return TRUE;
        }

    }



    public function updateCartItem($cart, $productId, $quantity )
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->update('AppBundle:Cart_items','c')
            ->set('c.quantity', ':quantity')
            ->set('c.updated_at', ':time')
            ->andWhere('c.product_id = :productId')
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 0')
            ->setParameter('productId', $productId)
            ->setParameter('quantity', $quantity)
            ->setParameter('time', new \DateTime("now"))
            ->setParameter('cart', $cart);

//        dump($cart); dump($productId); dump($quantity); dump($qb->getQuery()); die();


        return $qb->getQuery()->execute();

    }




    public function calculateCartTotal($cart)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select(' SUM( c.quantity * p.price)')
            ->from('AppBundle:Cart_items', 'c')
            ->leftJoin(
                'AppBundle:Products',
                'p',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'p.id = c.product_id'
            )
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 0')
            ->setParameter('cart', $cart);

        return $qb->getQuery()->getResult();

    }


    public function findCartItem($cart_item_id)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('c')
            ->from('AppBundle:Cart_items', 'c')
            ->where('c.id = :cart_item_id')
            ->setParameter('cart_item_id', $cart_item_id);

        return $qb->getQuery()->getResult();
    }

    public function removeCartItem($cart_item_id)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->delete('AppBundle:Cart_items', 'c')
            ->where('c.id = :cart_item_id')
            ->setParameter('cart_item_id', $cart_item_id);

        return $qb->getQuery()->execute();

    }

    public function removeAllCartItems($cart)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->delete('AppBundle:Cart_items', 'c')
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 0')
            ->setParameter('cart', $cart);

        return $qb->getQuery()->execute();

    }


    public function getQuantity($productId, $cart)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('c')
            ->from('AppBundle:Cart_items', 'c')
            ->andWhere('c.product_id = :productId')
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 0')
            ->setParameter('productId', $productId)
            ->setParameter('cart', $cart);


        return $qb->getQuery()->getResult();

    }

    public function increase_quantity($cart_item_id,$new_cart_quantity)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->update('AppBundle:Cart_items','c')
            ->set('c.quantity', ':quantity')
            ->andWhere('c.id = :cart')
            ->andWhere('c.is_wishlisted = 0')
            ->setParameter('cart', $cart_item_id)
            ->setParameter('quantity', $new_cart_quantity);


        return $qb->getQuery()->execute();

    }



    public function isItemsWishListed($productId, $cart)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('c')
            ->from('AppBundle:Cart_items', 'c')
            ->andWhere('c.product_id = :productId')
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 1')
            ->setParameter('productId', $productId)
            ->setParameter('cart', $cart);

        $isItemsWishListed = $qb->getQuery()->getResult();

        if (empty($isItemsWishListed)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function removeWishListItem($cart_item_id)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->delete('AppBundle:Cart_items', 'c')
            ->andWhere('c.product_id = :cart_item_id')
            ->andWhere('c.is_wishlisted = 1')
            ->setParameter('cart_item_id', $cart_item_id);

        return $qb->getQuery()->execute();

    }


    public function findWishListProducts($cart)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('c')
            ->from('AppBundle:Cart_items', 'c')
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 1')
            ->setParameter('cart', $cart);

        return $qb->getQuery()->getResult();
    }


    public function removeAllWishListItems($cart)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->delete('AppBundle:Cart_items', 'c')
            ->andWhere('c.cart_id = :cart')
            ->andWhere('c.is_wishlisted = 1')
            ->setParameter('cart', $cart);

        return $qb->getQuery()->execute();

    }


    public function moveItemToCart($cart_item_id)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->update('AppBundle:Cart_items','c')
            ->set('c.is_wishlisted','0')
            ->set('c.updated_at', ':time')
            ->where('c.id = :cart')
            ->setParameter('cart', $cart_item_id)
            ->setParameter('time', new \DateTime("now"));


        return $qb->getQuery()->execute();

    }


}
