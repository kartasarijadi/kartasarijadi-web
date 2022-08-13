<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class TestHome extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testIndex()
    {
        $result = $this->call('get', "/");
        $result->assertOK();
        $result->assertSeeElement('header');
        $result->assertSeeElement('main');
        $result->assertSee('Visi & Misi', 'h2');
        $result->assertSee('Kegiatan Kami', 'h2');
        $result->assertSee('Siapa Kami', 'h2');
        $result->assertSeeElement('footer');
        $result->getActualOutputForAssertion();
    }

    public function testHistory()
    {
        $result = $this->call('get', "sejarah");
        $result->assertOK();
        $result->assertSee('Berdiri Membantu Masyarakat Sarijadi', 'h1');
        $result->assertSeeElement('footer');
    }
    public function testContactUs()
    {
        $result = $this->call('get', "hubungi-kami");
        $result->assertOK();
        $result->assertSee('Hubungi Kami', 'h1');
        $result->assertSeeElement('input[name=message_name]');
        $result->assertSeeElement('input[name=message_whatsapp]');
        $result->assertSeeElement('select[name=message_type]');
        $result->assertSeeElement('textarea[name=message_text]');
        $result->assertSeeElement('footer');
    }
}
