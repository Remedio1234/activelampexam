<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Api\ApiExam;
use App\Entity\Shortend;
use App\Repository\ShortendRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ExamController extends AbstractController
{
    /**
    * @Route("/show", methods="GET")
    */
    public function show(ShortendRepository $sr)
    {
        $se = $sr->transformAll();
        $api = new ApiExam();
        return $api->respond($se);
    }

    /**
    * @Route("/create", methods="POST")
    */
    public function create(Request $request, ShortendRepository $sr, EntityManagerInterface $em)
    {
       
        $api = new ApiExam();

        if (! $request)
            return $api->respondValidationError('Please provide a valid request!');
        

        // validate the title
        if (! $request->get('url'))
            return $api->respondValidationError('Please provide a url!');
        
        $hash_code  = $api->generateRandomString(6);
        $protocol   = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
        $set_url    = $protocol.$_SERVER['HTTP_HOST'];

        // persist the new Shortend
        $se = new Shortend;
        $se->setUrl($request->get('url'));
        $se->setHash($hash_code);
        $se->setTinyurl($set_url.'/'.$hash_code);
        $se->setCreatedAt(date('Y-m-d H:i:d'));
        $em->persist($se);
        $em->flush();

        return $api->respondCreated($sr->transform($se));
    }

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

        // $api = new ApiExam();
        // $hello = $api->hello();
        $number = random_int(0, 100);

        return $this->render('exam/show.html.twig', [
            'number' => $number,
            'answers' => [
                [
                    'name' => 'je'
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
