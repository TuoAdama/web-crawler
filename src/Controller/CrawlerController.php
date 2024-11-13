<?php

namespace App\Controller;

use Facebook\WebDriver\Chrome\ChromeOptions;
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
        $serverUrl = "selenium:4444'";
        $options = new ChromeOptions();
        $options->addArguments(["--headless"]);
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $driver = RemoteWebDriver::create($serverUrl, $capabilities);

       // $driver->get("https://esii-orion.com/orion-reservation/slots?account=EVMSPI&config=SEJOURUSARNVT&usemode=app&code=REN1");
        //$driver->get('https://en.wikipedia.org/wiki/Selenium_(software)');
        //$driver->takeScreenshot("demo.png");

        return new JsonResponse([$serverUrl]);
    }
}
