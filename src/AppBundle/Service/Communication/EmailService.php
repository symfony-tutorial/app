<?php

namespace AppBundle\Service\Communication;

use AppBundle\Communication\Email\Message;
use AppBundle\Communication\Email\ProviderInterface;
use AppBundle\Service\UtilService;

class EmailService
{

    const ID = 'app.email';

    private $providers = array();
    private $providerIndex = -1;

    /**
     *
     * @var UtilService
     */
    private $utilService;
    private $maxPerSecond;

    function __construct(UtilService $utilService, $maxPerSecond) {
        $this->utilService = $utilService;
        $this->maxPerSecond = $maxPerSecond;
    }

    public function addProvider(ProviderInterface $provider) {
        $this->providers[] = $provider;
    }

    public function send(Message $message) {
        $this->utilService->enterConcurrentSection('email', $this->maxPerSecond, 1);

        $this->incrementIndex();
        $provider = $this->providers[$this->providerIndex];
        $result = $provider->send($message);

        $this->utilService->exitConcurrentSection('email');
        return $result;
    }

    private function incrementIndex() {
        $this->providerIndex++;
        if ($this->providerIndex > count($this->providers) - 1) {
            $this->providerIndex = 0;
        }
    }

}
