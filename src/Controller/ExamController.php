<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Api\ApiExam;
use App\Entity\Shortend;
use App\Repository\ShortendRepository;

class ExamController extends AbstractController
{
    /**
     * @Route("/", name="exam")
     */
    public function index(): Response
    {
        // return homepage
        return $this->render('exam/show.html.twig', []);
    }

    /**
    * @Route("/create", methods="POST")
    */
    public function create(Request $request, ShortendRepository $sr, EntityManagerInterface $em)
    {
        $api = new ApiExam();

        if (! $request)
            return $api->respondValidationError('Please provide a valid request!');

        //check url exist
        // if($sr->findOneBy(['url' => $request->get('url')]))
        //     return $api->respondValidationError('URL Already Exist');

        if (!(strpos($request->get('url'), 'http') !== false) && !(strpos($request->get('url'), 'https') !== false)) 
            return $api->respondValidationError('URL must contain http or https');
        
        // validate the URL
        if (! $request->get('url'))
            return $api->respondValidationError('Please provide a url!');
        
        $hash_code  = $api->generateRandomString(6);
        $protocol   = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 
                        $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
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
    * @Route("/{hash}", methods="GET")
    */
    public function getRow($hash, ShortendRepository $sr)
    {   
        $api = new ApiExam();
        $hash_url = $sr->findOneBy(['hash' => $hash]);

        if (! $hash_url)
            return $api->respondNotFound();

        return $this->redirect($hash_url->getUrl(), 301);
    }

    /**
    * @Route("/show", methods="GET")
    */
    public function show(ShortendRepository $sr)
    {
        $se = $sr->transformAll();
        $api = new ApiExam();
        return $api->respond($se);
    }
}
