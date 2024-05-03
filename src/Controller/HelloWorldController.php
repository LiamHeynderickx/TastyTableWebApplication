<?php
// src/Controller/HelloWorldController.php
// src/Controller/HelloWorldController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloWorldController extends AbstractController
{

    #[Route('/',name:'logIn')]
    public function homePage(): Response
    {

        $tracks=[['name'=>'ramazan','surname'=>'yetismis'],
            ['name'=>'ramo','surname'=>'yetismis'],
            ['name'=>'ramazan','surname'=>'yetismis']

           ];
        return $this->render('Pages/index.html.twig',[
           'title'=> 'Ramazan and Ramo',
            'tracks'=>$tracks,
        ]);
    }


    #[Route('/register',name:'SignIn')]
    public function register(): Response
    {

        $tracks=[['name'=>'ramazan','surname'=>'yetismis'],
            ['name'=>'ramo','surname'=>'yetismis'],
            ['name'=>'ramazan','surname'=>'yetismis']

        ];
        return $this->render('Pages/register.html.twig',[
            'title'=> 'Ramazan and Ramo',
            'tracks'=>$tracks,
        ]);
    }
}

