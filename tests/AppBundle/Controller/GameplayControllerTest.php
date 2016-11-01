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

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Tests\AppBundle\AuthenticatedWebTestCase;

/**
 * Class GameplayControllerTest
 * @package Tests\AppBundle\Controller
 */
class GameplayControllerTest extends AuthenticatedWebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createAuthenticatedClient();
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
     * @test Make sure that authenticated requests to obtain a new game are working and have the correct data.
     */
    public function testAuthenticatedRequestToGameAction()
    {
        $url = $this->client->getContainer()->get('router')->generate('game');

        $this->client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $encoded = $this->client->getResponse()->getContent();

        $decoder = new JsonDecode();
        $decoded = $decoder->decode($encoded, JsonEncoder::FORMAT, ['json_decode_associative' => true]);

        $this->assertCount(3, $decoded);

        $keys = ['user', 'gameset', 'stats'];
        foreach($keys as $key) {
            $this->assertArrayHasKey($key, $decoded);
            $this->assertNotNull($decoded[$key]);
        }
    }

    /**
     * @test Make sure that authenticated requests to the API work.
     */
    public function testAuthenticatedRequestToPlayAction()
    {
        $url = $this->client->getContainer()->get('router')->generate('game');
        $this->client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/json']);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $gameset = json_decode($this->client->getResponse()->getContent());

        $params = [
            'make_move_form' => [
                'move' => 'rock',
                'game' => $gameset->gameset->games[0]->guid,
                'gameset' => $gameset->gameset->guid
            ]
        ];

        $url = $this->client->getContainer()->get('router')->generate('play');

        $this->client->request(Request::METHOD_POST, $url, $params, [], ['HTTP_ACCEPT' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test Play a game with an invalid move that does not belong to any game type.
     */
    public function testPlayActionWithInvalidMove()
    {
        $move = 'spock';

        $url = $this->client->getContainer()->get('router')->generate('game');
        $this->client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/json']);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $gameset = json_decode($this->client->getResponse()->getContent());

        $params = [
            'make_move_form' => [
                'move' => $move,
                'game' => $gameset->gameset->games[0]->guid,
                'gameset' => $gameset->gameset->guid
            ]
        ];

        $url = $this->client->getContainer()->get('router')->generate('play');

        $this->client->request(Request::METHOD_POST, $url, $params, [], ['HTTP_ACCEPT' => 'application/json']);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $response = $this->client->getResponse()->getContent();

        $decoder = new JsonDecode();
        $decoded = $decoder->decode($response, JsonEncoder::FORMAT, ['json_decode_associative' => true]);

        $this->assertArrayHasKey('errors', $decoded);
        $this->assertCount(1, $decoded['errors']);

        $this->assertEquals('The move "'.$move.'" does not exist.', $decoded['errors'][0]);
    }
}
