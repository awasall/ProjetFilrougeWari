<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartenaireControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>"diama@gmail.com",
            'PHP_AUTH_PW'=>"passer"

        ]);
        $crawler = $client->request('GET', '/api/listepartenaire');

        $rep=$client->getResponse();
        $this->assertSame(200,$client->getResponse()->getStatusCode());
        //$this->assertJsonStringEqualsJsonString($jsonstring,$rep->getContent());
    }
    public function testAjoutpartenaire()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>"diama@gmail.com",
            'PHP_AUTH_PW'=>"passer"

        ]);
        $crawler = $client->request('POST', '/api/partenaire',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "ninea":"0001L",
            "raisonsociale":"Ndoyeservice",
            "adresse":"Dakar",
            "telephone":"787878795",
            "numerocompte":"473659",
            "solde":"0",
            "email":"ndoye@gmail.com"
            }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }
    

    public function testAjoutDepot()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>"ousseynou@gmail.com",
            'PHP_AUTH_PW'=>"passer"

        ]);
        $crawler = $client->request('POST', '/api/depot',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "montant":800000,
            "partenaire":46
            }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }


    public function testAjoutUtilisateur()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>"diama@gmail.com",
            'PHP_AUTH_PW'=>"passer"

        ]);
        $crawler = $client->request('POST', '/api/register',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "email":"jean@gmail.com",
            "password":"passer",
            "password_confirmation":"passer",
            "roles":["ROLE_caissier"],
            "nomcomplet":"jean sall",
            "propriete":"Wari",
            "adresse":"parcelles",
            "telephone":"7676895169",
            "statut":"actif"
            
            }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }
}
