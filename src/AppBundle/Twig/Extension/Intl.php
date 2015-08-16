<?php

namespace AppBundle\Twig\Extension;

class Intl extends \Twig_Extensions_Extension_Intl
{

    public function getFilters() {
        return array_merge(parent::getFilters(), array(
            new \Twig_SimpleFilter(
                    'localizedcurrency', array($this, 'localizedCurrencyFilter')
            )
        ));
    }

    public function localizedCurrencyFilter
    ($number, $currency = null, $locale = null, $forceDefault = false) {
        if (!$forceDefault) {
            $number /=100;
        }
        return twig_localized_currency_filter($number, $currency, $locale);
    }

}
