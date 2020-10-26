<?php

namespace App\Twitch;

use GuzzleHttp\Client;

class Helix
{
    protected $token;
    protected $clientId;
    protected $client;

    public function __construct($clientId, $token)
    {
        $this->clientId = $clientId;
        $this->token = $token;
        $this->client = new Client(['base_uri' => 'https://api.twitch.tv/helix/']);
    }

    protected function options()
    {
        return [
            'headers' => [
                'Client-ID' => $this->clientId,
                'Authorization' => 'Bearer ' . $this->token,
            ],
            'exceptions' => false,
        ];
    }

    public function getSubscriptions(int $broadcasterId): array
    {
        $subscriptions = [];
        $perPage = 100;

        $url = "subscriptions?broadcaster_id=$broadcasterId&first=$perPage";
        while (true) {
            $response = $this->client->request('GET', $url, $this->options());

            if ($response->getStatusCode() !== 200) {
                abort('200', '', ['Location' => route('index')]);
            }

            $decodedResponse = json_decode($response->getBody()->getContents());
            $subscriptions = array_merge($subscriptions, $decodedResponse->data);

            if (count($decodedResponse->data) < $perPage) {
                return $subscriptions;
            }

            $url = "subscriptions?broadcaster_id=$broadcasterId&first=$perPage&after=$decodedResponse->pagination->cursor";
        }
    }

    public function getUsers(array $ids): array
    {
        $users = [];
        $perPage = 100;
        foreach (array_chunk($ids, $perPage) as $ids) {
            $url = 'users?id=' . implode('&id=', $ids);

            $response = $this->client->request('GET', $url, $this->options());

            if ($response->getStatusCode() !== 200) {
                abort('200', '', ['Location' => route('index')]);
            }

            $decodedResponse = json_decode($response->getBody()->getContents());
            $users = array_merge($users, $decodedResponse->data);
        }
        return $users;
    }
}
