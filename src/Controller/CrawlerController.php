<?php

namespace App\Controller;

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

    public function __construct(
        private ParameterBagInterface $parameterBag,
    )
    {
    }

    /**
     * @throws ConfigurationException
     */
    #[Route('/crawler', name: 'app_crawler')]
    public function index(LoggerInterface $logger, TwilioService $twilioService): JsonResponse
    {
        $serverUrl = "selenium:4444";
        $driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome(), 3000);
        $driver->get("https://esii-orion.com/orion-reservation/slots?account=EVMSPI&config=SEJOURUSARNVT&usemode=app&code=REN1");
        sleep(5);
        $element = $driver->findElement(WebDriverBy::cssSelector('.user-msg'));
        $text = $element->getText();
        $time = (new DateTime())->format('Y-m-d-H:i:s');
        $driver->takeScreenshot("screenshot-$time.png");
        $driver->quit();
        $searchText = $this->parameterBag->get('search_text');
        if (trim($searchText) === trim($text)){
            $twilioService->send("0751097177", "Un RDV été trouvé");
        }
        return new JsonResponse([$serverUrl]);
    }
}
