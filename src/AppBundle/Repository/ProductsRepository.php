<?php
/**
 * Created by PhpStorm.
 * User: Teka
 * Date: 9/1/2018
 * Time: 2:57 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ProductsRepository extends EntityRepository
{

    public function findAllProducts()
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('p')
            ->from('AppBundle:Products', 'p')
            ->orderBy(' p.product_name','ASC');

        return $qb->getQuery()->getResult();
    }



    public function findById($productId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('p')
            ->from('AppBundle:Products', 'p')
            ->where(' p.id = :productId')
            ->setParameter('productId', $productId);

        return $qb->getQuery()->getResult();

    }


    public function decrease_quantity($product,$new_product_quantity)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->update('AppBundle:Products','p')
            ->set('p.quantity', ':quantity')
            ->where('p.id = :productId')
            ->setParameter('productId', $product)
            ->setParameter('quantity', $new_product_quantity);


        return $qb->getQuery()->execute();

    }




}