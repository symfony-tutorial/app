<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vendor
 *
 * @ORM\Table(name="translation_message")
 * @ORM\Entity
 */
class TranslationMessage
{

    const REPOSITORY = 'AppBundle:TranslationMessage';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", nullable=true)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", nullable=true)
     */
    private $domain;

    function getId() {
        return $this->id;
    }

    function getKey() {
        return $this->key;
    }

    function getLocale() {
        return $this->locale;
    }

    function getValue() {
        return $this->value;
    }

    function getDomain() {
        return $this->domain;
    }

    function setKey($key) {
        $this->key = $key;
    }

    function setLocale($locale) {
        $this->locale = $locale;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setDomain($domain) {
        $this->domain = $domain;
    }

}
