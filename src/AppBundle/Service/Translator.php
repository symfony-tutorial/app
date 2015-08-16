<?php

namespace AppBundle\Service;

use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Translator implements TranslatorInterface, TranslatorBagInterface
{

    /**
     *
     * @var TranslatorInterface 
     */
    private $translator;

    function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function trans($id, array $parameters = array(), $domain = null, $locale = null) {
        $translated = $this->translator->trans($id, $parameters, $domain, $locale);
        if (strpos($translated, '@') === 0) {
            $id = substr($translated, 1);
            $translated = $this->translator->trans($id, $parameters, $domain, $locale);
        }
        return $translated;
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array($this->translator, $name), $arguments);
    }

    public function getLocale() {
        return $this->translator->getLocale();
    }

    public function setLocale($locale) {
        return $this->translator->setLocale($locale);
    }

    public function transChoice
    ($id, $number, array $parameters = array(), $domain = null, $locale = null) {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $locale);
    }

    public function getCatalogue($locale = null) {
        return $this->translator->getCatalogue($locale);
    }

}
