<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart_items;
use AppBundle\Entity\Carts;
use AppBundle\Entity\Products;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CartsController extends Controller
{
    /**
     * @Route("/viewCart", name="viewCart")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {

        //Get Cart Session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart');

        //Get Cart List
        $cart_list = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->findAllProductsInCart($cart);

        return $this->render('shop/cart.html.twig', array(
            'cart_list' => $cart_list));

    }


    /**
     * Adds a product to the shopping cart
     * @Route("/addToCart", name="addToCart")
     */
    public function addToCartAction(Request $request)
    {

        //Check empty cart list
        $empty = false;

        //Get Cart Session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart');

        //Check selected product availability
        $productId = $request->get('productId');

        $selected_product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findById($productId);

        if (empty($selected_product)) {

            $this->addFlash('warning', 'The selected product is not available');
            return $this->redirectToRoute('homepage');

        } else {

            //Check selected product quantity
            $get_product_quantity = $selected_product[0]['quantity'];

            if ($get_product_quantity == 0){
                $this->addFlash('warning', 'The selected product is out of stock');
                return $this->redirectToRoute('homepage');
            }else{

                //Check if the selected product already in wishlist
                $isItemsWishListed = $this->getDoctrine()
                    ->getRepository(Cart_items::class)
                    ->isItemsWishListed($productId, $cart);


                //if exist remove from wish list
                if ($isItemsWishListed) {

                    $removeWishListItem = $this->getDoctrine()
                        ->getRepository(Cart_items::class)
                        ->removeWishListItem($productId, $cart);

                }

                $isItemExisted = $this->getDoctrine()
                    ->getRepository(Cart_items::class)
                    ->isItemsExisted($productId, $cart);

                //if not exist => Add new cart item
                if (!$isItemExisted) {

                    $cart_item = new Cart_items();

                    $em = $this->getDoctrine()->getManager();
                    $product = $em->getReference('AppBundle\Entity\Products', $selected_product[0]['id']);
                    $cartId = $em->getReference('AppBundle\Entity\Carts', $cart);


                    $cart_item->setProductId($product);
                    $cart_item->setCartId($cartId);
                    $cart_item->setQuantity(1);
                    $cart_item->setIsWishlisted(false);
                    $cart_item->setUpdatedAt(new \DateTime());

                    $em->persist($cart_item);
                    $em->flush();
                }


               $em = $this->getDoctrine()->getManager()->clear();

                //Get final cart list to render
                $cart_list = $this->getDoctrine()
                    ->getRepository(Cart_items::class)
                    ->findAllProductsInCart($cart);


                return $this->render('shop/cart.html.twig', array(
                    'cart_list' => $cart_list, 'selected_product' => $selected_product));
            }

        }

    }


    /**
     * Update Cart Items
     * @Route("/updateCart", name="updateCart")
     */
    public function updateCartAction(Request $request)
    {

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();

        $cart = $session->get('cart');

        $productId = $request->get('productId');
        $quantity = $request->get('qty');


        $cart_total = $request->get('cart_total');

        $product_array = array_combine($productId,$quantity);

        foreach ($product_array as $product => $quantity){

            $get_product = $this->getDoctrine()
                ->getRepository(Products::class)
                ->findById($product);

            $productQty = $get_product[0]['quantity'];
            $productName = $get_product[0]['product_name'];

            if ($productQty < $quantity){
                $this->addFlash('warning', 'Only '.$productQty.' of '.$productName.' are available');
                return $this->redirectToRoute('viewCart');
            }else{

                $updateCartItem = $this->getDoctrine()
                    ->getRepository(Cart_items::class)
                    ->updateCartItem($cart,$product,$quantity);

//                dump($updateCartItem); die();

                if($updateCartItem){
                    //Decrease quantity in stock after update the cart
                    $new_product_quantity = $productQty - $quantity;

                    $decrease_product_quantity = $this->getDoctrine()
                        ->getRepository(Products::class)
                        ->decrease_quantity($product,$new_product_quantity);

                }else{
                    $this->addFlash('warning', 'An error occurred while saving '.$productName. ' item in your cart');
                    return $this->redirectToRoute('viewCart');
                }

            }
        }

        $calculate_cart_total = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->calculateCartTotal($cart);

        $cart_total = $calculate_cart_total[0][1];

        $updateCartTotal = $this->getDoctrine()
            ->getRepository(Carts::class)
            ->updateCartTotal($cart,$cart_total);

        $this->addFlash('success', 'The cart is updated successfully');
        return $this->redirectToRoute('homepage');

    }



    /**
     * Remove Cart item
     * @Route("/removeCartItem", name="removeCartItem")
     */
    public function removeCartItemtAction(Request $request)
    {
        $response = new JsonResponse();
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();

        $cart = $session->get('cart');

        $cart_item_id = $request->get('cart_item_id');

        $removeCartItem = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->removeCartItem($cart_item_id);

        if ($removeCartItem) {
            $cart_list = $this->getDoctrine()
                ->getRepository(Cart_items::class)
                ->findAllProductsInCart($cart);
            if (count($cart_list) == 0){
                $response->setData("last item");
            }else{
                $response->setData("success");
            }
//            $this->addFlash('success', 'The cart item is removed successfully');
        } else {
            $response->setData("fail");
//            $this->addFlash('warning', 'An error occurred while removing the cart item');
        }

        return $response;

    }


    /**
     * Remove All Cart items
     * @Route("/removeAllCartItems", name="removeAllCartItems")
     */
    public function removeAllCartItemsAction(Request $request)
    {
        $response = new JsonResponse();
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();

        $cart = $session->get('cart');

//        $cart_item_id = $request->get('cart_item_id');

        $removeAllCartItems = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->removeAllCartItems($cart);

        if ($removeAllCartItems) {

                $response->setData("success");
            }
//            $this->addFlash('success', 'The cart item is removed successfully');
         else {
            $response->setData("fail");
//            $this->addFlash('warning', 'An error occurred while removing the cart item');
        }

        return $response;

    }



}
