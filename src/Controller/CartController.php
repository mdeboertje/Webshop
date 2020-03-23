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
use Dompdf\Dompdf;
use Dompdf\Options;


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



        if ($cart != '') {
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
            $this->addFlash('error', 'This item does not exist. Please Try Again');
            return $this->redirect($this->generateUrl('cart'));
        } else {
            if (isset($cart[$id])) {

                ++$cart[$id];
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
    public function checkoutAction(ProductRepository $productRepository, KlantRepository $klantRepository, \Swift_Mailer $mailer)
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

        $message = (new \Swift_Message('Nieuwe bestelling met id #' . $factuur->getId()))
            ->setFrom('no-reply@squidit.nl')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/order.html.twig',
                    ['cart' => $cart,
                        'factuur' => $factuur,
                        'product' => $productRepository]
                ),
                'text/html'
            );

        $mailer->send($message);



        $session->clear();

        return $this->redirectToRoute('success');



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
        if (!$cart[$id]) {
            $this->redirect($this->generateUrl('cart'));
        }

        // check if the $id already exists in it.
        if (isset($cart[$id])) {
            $cart[$id] = $cart[$id] - 1;
            if ($cart[$id] < 1) {
                unset($cart[$id]);
            }
        } else {
            return $this->redirect($this->generateUrl('cart'));
        }

        $session->set('cart', $cart);

        //echo('<pre>');
        //print_r($cart); echo ('</pre>');die();

        return $this->redirect($this->generateUrl('cart'));
    }


    /**
     * @Route("/", name="success")
     */
    public function downloadPDF(ProductRepository $productRepository, KlantRepository $klantRepository){
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
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView(
        // templates/emails/registration.html.twig
            'default/pdf.html.twig',
            ['cart' => $cart,
                'factuur' => $factuur,
                'product' => $productRepository]


        );

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream($factuur->getId().".pdf", [
            "Attachment" => true
        ]);
    }

}
