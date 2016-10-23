<?php
/**
 * MIT License
 *
 * Copyright (c) 2016 Ricardo Velhote
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GameplayControllerTest
 * @package Tests\AppBundle\Controller
 */
class GameplayControllerTest extends WebTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = '@rvelhote', $password = 'x')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/login',
            array(
                '_username' => $username,
                '_password' => $password,
            )
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    /**
     * @test Verify that unauthenticated requests to the API (when getting a gameset) are denied.
     */
    public function testUnauthenticatedRequestsToGameAction()
    {
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('game');

        $client->request(Request::METHOD_GET, $url);
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    /**
     * @test Verify that unauthenticated requests to the API (when playing a game) are denied.
     */
    public function testUnauthenticatedRequestsToPlayAction()
    {
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('play');

        $client->request(Request::METHOD_POST, $url);
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    /**
     * @test Make sure that authenticated requests to the API work.
     */
    public function testAuthenticatedRequestToGameAction()
    {
        $client = $this->createAuthenticatedClient();
        $url = $client->getContainer()->get('router')->generate('game');

        $client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test Make sure that authenticated requests to the API work.
     */
    public function testAuthenticatedRequestToPlayAction()
    {
        $client = $this->createAuthenticatedClient();

        $url = $client->getContainer()->get('router')->generate('game');
        $client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/json']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $gameset = json_decode($client->getResponse()->getContent());

        $params = [
            'form' => [
                'move' => 'rock',
                'game' => $gameset->gameset->games[0]->guid,
                'gameset' => $gameset->gameset->guid
            ]
        ];

        $url = $client->getContainer()->get('router')->generate('play');

        $client->request(Request::METHOD_POST, $url, $params, [], ['HTTP_ACCEPT' => 'application/json']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
