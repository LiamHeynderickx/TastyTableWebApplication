<?php
// src/Controller/HelloWorldController.php
// src/Controller/HelloWorldController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloWorldController
{

    #[Route('/welcome/vendor/autoload_runtime.php')]
    public function welcome(string $name): Response
    {
        $htmlContent = "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">#
    <div></div>
    <title>Welcome big dawg</title>
</head>
<body>
    <h1>Welcome, " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "!</h1>
</body>
</html>";

        return new Response($htmlContent);
    }
}

