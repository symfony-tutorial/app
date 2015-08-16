<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TranslatorTest extends WebTestCase
{

    public function setUp() {
        static::bootKernel();
    }

    public function testTranslate() {
        $translator = static::$kernel->getContainer()->get('translator.default');
        $from = $translator->trans('confirmation.from', array(), 'email', 'en');
        $this->assertEquals('orders@site.com', $from);
    }
    
    public function testTranslateWithReference() {
        $translator = static::$kernel->getContainer()->get('translator.default');
        $from = $translator->trans('newsletter_christmas.from', array(), 'email', 'en');
        $defaultFrom = $translator->trans('default_newsletter_sender', array(), 'email', 'en');
        $this->assertEquals($defaultFrom, $from);
    }

}
