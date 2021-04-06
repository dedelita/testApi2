<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PersonneController extends AbstractController
{
    private $persRepository;
    
    public function __construct(PersonneRepository $persRepository)
    {
        $this->persRepository = $persRepository;
    }

    /**
     * @Route("/addPersonne", name="personne", methods="POST")
     */
    public function addPersonne(Request $request): JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $nom = $request->get("nom");
        $prenom = $request->get("prenom");
        $dateNaissance = $request->get("dateNaissance");

        $dn = new \DateTime(($dateNaissance));
        $now = new \DateTime(date("Y-m-d"));
        $age = date_diff($now, $dn);
        $age = $age->format("%Y");
        $pers = $this->persRepository->add($nom, $prenom, $dateNaissance, $age);
        
        $json_pers= $serializer->serialize($pers, 'json');
        return new JsonResponse(["pers" => $json_pers], Response::HTTP_CREATED);
    }

    /**
     * @Route("/getPersonnesAsc", name="get_personnes_asc", methods="GET")
     */
    public function getPersonnesAsc(Request $request): JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $listPers = $this->persRepository->findAllAsc();

        $json_list = $serializer->serialize($listPers, 'json');
        
        return new JsonResponse(["list" => $json_list], Response::HTTP_OK);
    }
}
