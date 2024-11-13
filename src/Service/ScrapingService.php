<?php

namespace App\Service;

use DateTime;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twilio\Exceptions\ConfigurationException;

readonly class ScrapingService
{
    private string $serverUrl;
    private string $rdvUrl;
    private string $notifyNumber;
    public function __construct(
        private TwilioService         $twilioService,
        private ParameterBagInterface $parameterBag,
    )
    {
        $this->serverUrl = $this->parameterBag->get('server_url');
        $this->rdvUrl = $this->parameterBag->get('rdv_url');
        $this->notifyNumber = $this->parameterBag->get('notify_number');
    }

    /**
     * @throws ConfigurationException
     */
    public function searchRdv(): void
    {
        $driver = RemoteWebDriver::create($this->serverUrl, DesiredCapabilities::chrome(), 5000);
        $driver->get($this->rdvUrl);
        sleep(5);
        $element = $driver->findElement(WebDriverBy::cssSelector('.user-msg'));
        $text = $element->getText();
        $time = (new DateTime())->format('Y-m-d-H:i:s');
        $driver->takeScreenshot("screenshot/screenshot-$time.png");
        $driver->quit();
        $searchText = $this->parameterBag->get('search_text');
        if (trim($searchText) !== trim($text)){
            $this->twilioService->send($this->notifyNumber, "Un RDV été trouvé. Cliquez ici: ".$this->rdvUrl);
        }
    }
}