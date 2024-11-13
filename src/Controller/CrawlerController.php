<?php

namespace App\Controller;

use App\Service\ScrapingService;
use App\Service\TwilioService;
use DateTime;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Twilio\Exceptions\ConfigurationException;

class CrawlerController extends AbstractController
{

    /**
     * @throws ConfigurationException
     */
    #[Route('/crawler', name: 'app_crawler')]
    public function index(ScrapingService $scrapingService): JsonResponse
    {
        $scrapingService->searchRdv();
        return new JsonResponse([]);
    }
}
