<?php

namespace App\Controller;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CrawlerController extends AbstractController
{
    #[Route('/crawler', name: 'app_crawler')]
    public function index(): JsonResponse
    {
        $serverUrl = "selenium:4444";
        $driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::firefox());

        $driver->get('https://google.com');

        return new JsonResponse([]);
    }
}
