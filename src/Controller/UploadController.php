<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UploadService;
use Psr\Log\LoggerInterface;

class UploadController extends AbstractController
{
    /**
     * @Route("/doUpload", name="do-upload")
     * @param Request $request
     * @param string $uploadDir
     * @param UploadService $uploader
     * @param LoggerInterface $logger
     * @return Response
     */
    public function doUpload(Request $request, string $uploadDir,
                             UploadService $uploader, LoggerInterface $logger): Response
    {
        $token = $request->get("token");

        if (!$this->isCsrfTokenValid('upload', $token)) {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }

        $file = $request->files->get('myfile');

        if (empty($file)) {
            return new Response("No file specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }

        $filename = $file->getClientOriginalName();
        $uploader->upload($uploadDir, $file, $filename);


        $content = utf8_encode(file_get_contents($uploadDir . '/' . $filename));  // load with UTF8

        $xml = simplexml_load_string($content);
        $json = json_encode($xml);
        $data = json_decode($json,TRUE);
        /*        $data = [];
                    foreach($xml as $key => $val) {
                        $data[$key] = $val; //echo "{$key}: {$val}";
                    }*/
        foreach ($data as $value) {
            foreach ($value as $product) {
                $sch = new Product();
                $sch->setOmschrijving($product['omschrijving']);
                $sch->setPrijs(floatval($product['prijs']));
                $sch->setBtw(intval($product['btw']));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($sch);
                $entityManager->flush();
            }
        }

        $this->addFlash('success', 'Import successful');

        return $this->redirectToRoute('product_index');
    }
}
