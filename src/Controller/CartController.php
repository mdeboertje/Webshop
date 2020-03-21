<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\Factuurregel;
use App\Entity\Klant;
use App\Repository\KlantRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();

        $cart = $session->get('cart', array());



        if ($cart != '')  {
            if (isset($cart) AND !empty($cart)) {
                $product = $productRepository->findAll();
            } else {
                return $this->render('/Cart/index.html.twig', array(
                    'empty' => true,
                    'product' => false,
                ));
            }




            return $this->render('/Cart/index.html.twig', array(
                'empty' => false,
                'product' => $product,
            ));
        } else {
            return $this->render('Cart/index.html.twig', array(
                'empty' => true,
            ));
        }
    }
    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function addAction($id, ProductRepository $productRepository)
    {
        // fetch the cart
        $product = $productRepository->find($id);
        //print_r($product->getId()); die;
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart', array());

        // check if the $id already exists in it.
        if ($product == null) {
            $this->addFlash('error', 'This item does not exist. Please Try Again' );
            return $this->redirect($this->generateUrl('cart'));
        } else {
            if (isset($cart[$id])) {

                ++$cart[$id];
                /*  $qtyAvailable = $product->getQuantity();
                  if ($qtyAvailable >= $cart[$id]['quantity'] + 1) {
                      $cart[$id]['quantity'] = $cart[$id]['quantity'] + 1;
                  } else {
                      $this->get('session')->setFlash('notice', 'Quantity     exceeds the available stock');
                      return $this->redirect($this->generateUrl('cart'));
                  } */
            } else {
                // if it doesnt make it 1
                $cart[$id] = 1;
                //$cart[$id]['quantity'] = 1;
            }

            $session->set('cart', $cart);
            //echo('<pre>');
            //print_r($cart); echo ('</pre>');die();
            return $this->redirect($this->generateUrl('cart'));

        }
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkoutAction(ProductRepository $productRepository, KlantRepository $klantRepository )
    {
        // verwerken van regels in de nieuwe factuur voor de huidige klant.
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        // $cart = $session->set('cart', '');
        $cart = $session->get('cart', array());

        // aanmaken factuur regel.

        $em = $this->getDoctrine()->getManager();
       $userAdress = $klantRepository->findOneBy(array('id' => $this->getUser()->getId()));



//         new UserAdress if no UserAdress found...
        if (!$userAdress) {
            $userAdress = new Klant();
            $userAdress->getId();
        }

        $factuur = new Factuur();
        $factuur->setFactuurDatum(new \DateTime("now"));
        $factuur->setVervalDatum(new \DateTime("now + 30 days"));
        $factuur->setKlantNummer($this->getUser());

        //var_dump($cart);
        // vullen regels met orderregels.
        // put invoice in dbase.
        if (isset($cart)) {
            // $data = $this->get('request_stack')->getCurrentRequest()->request->all();
            $em->persist($factuur);
            $em->flush();
            // put basket in dbase
            foreach ($cart as $id => $quantity) {
                $regel = new Factuurregel();
                $regel->setFactuurNummer($factuur);

                $em = $this->getDoctrine()->getManager();
                $product = $productRepository->find($id);

                $regel->setProductAantal($quantity);
                $regel->setProductCode($product);

                $em = $this->getDoctrine()->getManager();
                $em->persist($regel);
                $em->flush();
            }
        }


        $session->clear();
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/remove/{id}", name="cart_remove")
     */
    public function removeAction($id)
    {
        // check the cart
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart', array());

        // if it doesn't exist redirect to cart index page. end
        if(!$cart[$id]) { $this->redirect( $this->generateUrl('cart') ); }

        // check if the $id already exists in it.
        if( isset($cart[$id]) ) {
            $cart[$id] = $cart[$id] - 1;
            if ($cart[$id] < 1) {
                unset($cart[$id]);
            }
        } else {
            return $this->redirect( $this->generateUrl('cart') );
        }

        $session->set('cart', $cart);

        //echo('<pre>');
        //print_r($cart); echo ('</pre>');die();

        return $this->redirect( $this->generateUrl('cart') );
    }

}
