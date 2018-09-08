<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart_items;
use AppBundle\Entity\Carts;
use AppBundle\Entity\Products;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class WishListController extends Controller
{
    /**
     * @Route("/viewWishList", name="viewWishList")
     */
    public function indexAction(Request $request)
    {

        //Get Cart Session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart');

        //Get Wish List
        $wish_list = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->findWishListProducts($cart);


        return $this->render('shop/wishList.html.twig', array(
            'wish_list' => $wish_list));

    }



    /**
     * Adds a product to the wishlist
     * @Route("/addToWishList", name="addToWishList")
     */
    /**
     * Adds a product to the wishlist
     * @Route("/addToWishList", name="addToWishList")
     */
    public function addToWishListAction(Request $request)
    {
        $response = new JsonResponse();

        //Get Cart Session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart');

        //Check selected product availability
        $productId = $request->get('product_id');

        $selected_product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findById($productId);

        if (empty($selected_product)) {

            $this->addFlash('warning', 'The selected product is not available');
            return $this->redirectToRoute('homepage');

        } else {

            //Check if the selected product already in wishlist
            $isItemsWishListed = $this->getDoctrine()
                ->getRepository(Cart_items::class)
                ->isItemsWishListed($productId, $cart);


            //if exist => increase quantity by one
            if ($isItemsWishListed) {

                $removeWishListItem = $this->getDoctrine()
                    ->getRepository(Cart_items::class)
                    ->removeWishListItem($productId, $cart);

                $message = 'Removed';

            } else {
                //if not exist => Add to wishlist
                $cart_item = new Cart_items();

                $em = $this->getDoctrine()->getManager();
                $product = $em->getReference('AppBundle\Entity\Products', $selected_product[0]['id']);
                $cartId = $em->getReference('AppBundle\Entity\Carts', $cart);

                $cart_item = new Cart_items();

                $cart_item->setProductId($product);
                $cart_item->setCartId($cartId);
                $cart_item->setQuantity(1);
                $cart_item->setIsWishlisted(true);
                $cart_item->setUpdatedAt(new \DateTime());

                $em->persist($cart_item);
                $em->flush();

                $message = 'Added';
            }


            $wish_list = $this->getDoctrine()
                ->getRepository(Cart_items::class)
                ->findWishListProducts($cart);


            $response->setData(array('message' => $message, 'wish_list' => count($wish_list)));


            return $response;
        }
    }


    /**
     * Remove Cart item
     * @Route("/removeWishListItem", name="removeWishListItem")
     */
    public function removeWishListItemAction(Request $request)
    {
        $response = new JsonResponse();
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();

        $cart = $session->get('cart');

        $cart_item_id = $request->get('cart_item_id');

        $removeCartItem = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->removeCartItem($cart_item_id);

        if ($removeCartItem) {
            $wish_list = $this->getDoctrine()
                ->getRepository(Cart_items::class)
                ->findWishListProducts($cart);
            if (count($wish_list) == 0) {
                $response->setData("last item");
            } else {
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
     * Remove All Wish list items
     * @Route("/removeAllWishListItems", name="removeAllWishListItems")
     */
    public function removeAllWishListItems(Request $request)
    {
        $response = new JsonResponse();
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();

        $cart = $session->get('cart');

//        $cart_item_id = $request->get('cart_item_id');

        $removeAllWishListItems = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->removeAllWishListItems($cart);

        if ($removeAllWishListItems) {
            $response->setData("success");
        } //            $this->addFlash('success', 'The cart item is removed successfully');
        else {
            $response->setData("fail");
//            $this->addFlash('warning', 'An error occurred while removing the cart item');
        }

        return $response;

    }


    /**
     * Move item from whishlist to cart
     * @Route("/moveToCart", name="moveToCart")
     */
    public function moveToCartAction(Request $request)
    {
        $response = new JsonResponse();

        //Get Cart Session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart');

        //Check selected product availability

        $cart_item_id = $request->get('cart_item_id');

        $findCartItem = $this->getDoctrine()
            ->getRepository(Cart_items::class)
            ->findCartItem($cart_item_id);

        $productId = $findCartItem[0]['product_id'];

        $selected_product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findById($productId);

        if (empty($selected_product)) {

            $this->addFlash('warning', 'The selected product is not available');
            $response->setData('homepage');

        } else {

            //Check product quantity
            $productQty = $selected_product[0]['quantity'];
            $productName = $selected_product[0]['product_name'];

            if ($productQty < 1) {
                $this->addFlash('warning', 'Only ' . $productQty . ' of ' . $productName . ' are available');
                $response->setData('wishlist');
            } else {

                //Check if the selected item already exist in the cart
                $isItemExisted = $this->getDoctrine()
                    ->getRepository(Cart_items::class)
                    ->isItemsExisted($productId, $cart);

                if ($isItemExisted) {

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


                    $this->addFlash('info', $productName . ' was already added in your cart');
                    $response->setData('cart');
                } else {

                    $cart_item = new Cart_items();

                    $em = $this->getDoctrine()->getManager();
                    $product = $em->getReference('AppBundle\Entity\Products', $selected_product[0]['id']);
                    $cartId = $em->getReference('AppBundle\Entity\Carts', $cart);

                    $cart_item = new Cart_items();

                    $cart_item->setProductId($product);
                    $cart_item->setCartId($cartId);
                    $cart_item->setQuantity(1);
                    $cart_item->setIsWishlisted(false);
                    $cart_item->setUpdatedAt(new \DateTime());

                    $em->persist($cart_item);
                    $em->flush();


                    $this->addFlash('success', $productName . ' added to the cart successfully');
                    $response->setData('cart');

                }

            }


        }

        return $response;
    }


}
