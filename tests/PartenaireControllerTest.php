<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartenaireControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/listepartenaire');

        $rep=$client->getResponse();
        $this->assertSame(401,$client->getResponse()->getStatusCode());
        //$this->assertJsonStringEqualsJsonString($jsonstring,$rep->getContent());
    }
    public function testAjoutOk()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/partenaire',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "ninea":"0001C",
            "raisonsociale":"multiservice",
            "adresse":"Dakar",
            "telephone":"776778275",
            "numerocompte":"654987231",
            "solde":"0",
            "email":"mohamed@gmail.com"
            }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }


    public function testAjoutDepot()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/depot',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "montant":700000,
            "partenaire":27
            }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }


    public function testAjoutUtilisateur()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/register',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "email":"ousseynou@gmail.com",
            "roles":["ROLE_caissier"],
            "password":"passer",
            "nomcomplet":"ousseynou sall",
            "propriete":"Wari",
            "adresse":"parcelles",
            "telephone":"769876542",
            "statut":"actif"
            
            }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }
}
