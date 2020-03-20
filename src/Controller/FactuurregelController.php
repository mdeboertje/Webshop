<?php

namespace App\Controller;

use App\Entity\Factuurregel;
use App\Form\FactuurregelType;
use App\Repository\FactuurregelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factuurregel")
 */
class FactuurregelController extends AbstractController
{
    /**
     * @Route("/", name="factuurregel_index", methods={"GET"})
     */
    public function index(FactuurregelRepository $factuurregelRepository): Response
    {
        return $this->render('factuurregel/index.html.twig', [
            'factuurregels' => $factuurregelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="factuurregel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $factuurregel = new Factuurregel();
        $form = $this->createForm(FactuurregelType::class, $factuurregel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($factuurregel);
            $entityManager->flush();

            return $this->redirectToRoute('factuurregel_index');
        }

        return $this->render('factuurregel/new.html.twig', [
            'factuurregel' => $factuurregel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factuurregel_show", methods={"GET"})
     */
    public function show(Factuurregel $factuurregel): Response
    {
        return $this->render('factuurregel/show.html.twig', [
            'factuurregel' => $factuurregel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="factuurregel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Factuurregel $factuurregel): Response
    {
        $form = $this->createForm(FactuurregelType::class, $factuurregel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factuurregel_index');
        }

        return $this->render('factuurregel/edit.html.twig', [
            'factuurregel' => $factuurregel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factuurregel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Factuurregel $factuurregel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factuurregel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($factuurregel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('factuurregel_index');
    }
}
