<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class UploadService
{
    private $logger;

    private $serializer;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
//        $encoders = [new XmlEncoder(), new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
//        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function upload($uploadDir, $file, $filename)
    {
        try {

            $file->move($uploadDir, $filename);

//            $testvar = file_get_contents($uploadDir+$filename);
//
//            $xml = new \SimpleXMLElement($testvar);
//
//            $product = $this->serializer->deserialize($xml, Product::class, 'xml');


        } catch (FileException $e) {

            $this->logger->error('failed to upload image: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }
}
