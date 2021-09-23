<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExamController extends AbstractController
{
    /**
     * @Route("/", name="exam")
     */
    public function index(): Response
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/ExamController.php',
        // ]);
        // dump($this);
        $number = random_int(0, 100);

        return $this->render('exam/show.html.twig', [
            'number' => $number,
            'answers' => [
                [
                    'name' => 'jose'
                ],
                [
                    'name' => 'aber'
                ]
            ]
        ]);
    }
    /**
     * @Route("/sample/{slug}", name="sample_url")
     */
    public function test($slug)
    {
    
        return new Response("HAHHAHAHAH".$slug);
    }
}
