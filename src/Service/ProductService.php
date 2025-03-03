<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductService {

    public static function csvExport(ProductRepository $productRepository): Response{
        $data = $productRepository->findAllWithoutId();    
        $encoders = [new CsvEncoder()];
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers,$encoders);
        $csvContent = $serializer->serialize($data,'csv');
    
        $response = new Response($csvContent);
        $response->headers->set('Content-Encoding', 'UTF-8');
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=export.csv');
        return $response;
    }
}