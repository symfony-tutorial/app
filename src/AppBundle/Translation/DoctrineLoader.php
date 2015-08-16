<?php

namespace AppBundle\Translation;

use AppBundle\Entity\TranslationMessage;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DoctrineLoader implements LoaderInterface
{

    /**
     *
     * @var EntityManager
     */
    private $manager;

    function __construct(EntityManager $manager) {
        $this->manager = $manager;
    }

    public function load($resource, $locale, $domain = 'messages') {
        $repository = $this->manager->getRepository(TranslationMessage::REPOSITORY);
        $messages = $repository->findBy(array('locale' => $locale));
        $catalogue = new MessageCatalogue($locale);
        $translations = array();
        foreach ($messages as $message) {
            $translations[$message->getKey()] = $message->getValue();
        }
        $catalogue->add($translations, $domain);
        return $catalogue;
    }

}
