<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TwitchController extends Controller
{
    public function getAuthorization()
    {
        $url = 'https://id.twitch.tv/oauth2/authorize' .
            '?client_id=' . env('TWITCH_KEY') .
            '&redirect_uri=' . env('TWITCH_REDIRECT_URI') .
            '&response_type=code' .
            '&scope=channel:read:subscriptions';

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $client = new Client(['base_uri' => 'https://id.twitch.tv/oauth2/token']);

        $url = '?client_id=' . env('TWITCH_KEY') .
            '&client_secret=' . env('TWITCH_SECRET') .
            '&code=' . $request->get('code') .
            '&grant_type=authorization_code' .
            '&redirect_uri=' . env('TWITCH_REDIRECT_URI');

        $response = $client->request('POST ', $url);

        session(
            [
                'token' => json_decode($response->getBody()->getContents())->access_token,
            ]
        );

        return redirect()->route('twitch.subscribers');
    }

    public function subscribers()
    {
        $broadcasterId = $this->getBroadcasterId();
        $subscribers = $this->getSubscribers($broadcasterId);

        $counts = [
            'prime' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
        ];

        foreach ($subscribers as $subscriber) {
            $counts[$subscriber['tier']] += 1;
        }

        return view('subscribers', compact('subscribers', 'counts'));
    }

    protected function getBroadcasterId()
    {
        $options = [
            'headers' => [
                'Client-ID' => env('TWITCH_KEY'),
                'Authorization' => 'Bearer ' . session('token'),
            ],
            'exceptions' => false,
        ];

        $client = new Client(['base_uri' => 'https://api.twitch.tv/helix/']);

        $response = $client->request('GET', 'users', $options);

        if ($response->getStatusCode() !== 200) {
            abort('200', '', ['Location' => route('index')]);
        }

        $decodedResponse = json_decode($response->getBody()->getContents());

        return $decodedResponse->data[0]->id;
    }

    protected function getSubscribers($broadcasterId)
    {
        $subscribers = [];
        $subscriptions = $this->getSubscriptions($broadcasterId);

        foreach ($subscriptions as $subscription) {
            if ($subscription['user_id'] !== $broadcasterId) {
                $subscribers[$subscription['user_id']] = [
                    'name' => $subscription['user_name'],
                    'tier' => $subscription['tier'] == "Prime" ? "prime" : (int)$subscription['tier'] / 1000,
                ];
            }
        }

        $subscribers = $this->fetchExtraInfo($subscribers);

        return $subscribers;
    }

    protected function fetchExtraInfo($subscribers)
    {
        foreach (array_chunk($subscribers, 100, true) as $hundredSubscribers) {
            $ids = array_keys($hundredSubscribers);
            $users = $this->getUsers($ids);
            foreach ($users as $user) {
                $subscribers[$user['id']]['displayName'] = $user['display_name'];
                $subscribers[$user['id']]['logo'] = $user['profile_image_url'];
            }
        }

        return $subscribers;
    }

    protected function getUsers($ids)
    {
        $options = [
            'headers' => [
                'Client-ID' => env('TWITCH_KEY'),
                'Authorization' => 'Bearer ' . session('token'),
            ],
            'exceptions' => false,
        ];

        $url = 'users?id=' . implode('&id=', $ids);

        $client = new Client(['base_uri' => 'https://api.twitch.tv/helix/']);

        $response = $client->request('GET', $url, $options);

        if ($response->getStatusCode() !== 200) {
            abort('200', '', ['Location' => route('index')]);
        }

        $decodedResponse = json_decode($response->getBody()->getContents(), true);

        return $decodedResponse['data'];
    }

    protected function getSubscriptions($broadcasterId)
    {
        $options = [
            'headers' => [
                'Client-ID' => env('TWITCH_KEY'),
                'Authorization' => 'Bearer ' . session('token'),
            ],
            'exceptions' => false,
        ];

        $subscriptions = [];
        $perPage = 100;

        do {
            $url = 'subscriptions?broadcaster_id=' . $broadcasterId .
                '&first=' . $perPage;

            if (isset($cursor)) {
                $url .= '&after=' . $cursor;
            }

            $client = new Client(['base_uri' => 'https://api.twitch.tv/helix/']);

            $response = $client->request('GET', $url, $options);

            if ($response->getStatusCode() !== 200) {
                abort('200', '', ['Location' => route('index')]);
            }

            $decodedResponse = json_decode($response->getBody()->getContents(), true);
            if (isset($decodedResponse['pagination']['cursor'])) {
                $cursor = $decodedResponse['pagination']['cursor'];
            }

            $subscriptions = array_merge($subscriptions, $decodedResponse['data']);
        } while (!empty($decodedResponse['data']));

        return $subscriptions;
    }
}
