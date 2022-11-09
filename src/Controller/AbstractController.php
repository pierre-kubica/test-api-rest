<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected Request $request;
    protected EntityManagerInterface $em;
    protected SerializerInterface $serializer;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->em = $em;
        $this->serializer = $serializer;
    }
}
