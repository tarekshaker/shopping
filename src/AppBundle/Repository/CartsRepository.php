<?php
/**
 * Created by PhpStorm.
 * User: Teka
 * Date: 9/1/2018
 * Time: 2:57 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CartsRepository extends EntityRepository
{

    public function check_session($session_id)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('c')
            ->from('AppBundle:Carts', 'c')
            ->where('c.session_id = :session_id')
            ->setParameter('session_id', $session_id);

        return $qb->getQuery()->getResult();


    }


    public function updateCartTotal($cart, $cart_total )
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->update('AppBundle:Carts','c')
            ->set('c.total_price', ':cart_total')
            ->where('c.id = :cart')
            ->setParameter('cart_total', $cart_total)
            ->setParameter('cart', $cart);

        return $qb->getQuery()->getResult();
    }



}