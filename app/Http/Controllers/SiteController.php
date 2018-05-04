<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;

class SiteController extends Controller
{
    protected $channelName;
    protected $channelId;
    protected $token;

    public function index()
    {
        return view('index');
    }

    public function twitch()
    {
        return Socialite::with('twitch')
            ->scopes(['channel_subscriptions'])
            ->redirect();
    }

    public function callback()
    {
        $user = Socialite::with('twitch')->user();

        session([
            'channelName' => $user->user['display_name'],
            'channelId' => $user->user['_id'],
            'token' => $user->token,
        ]);

        return redirect()->route('twitch-subscribers');
    }

    public function subscribers()
    {
        $this->channelName = session('channelName');
        $this->channelId = session('channelId');
        $this->token = session('token');

        $allSubscribers = [];
        $offset = 0;

        do {
            $fetchedSubscribers = $this->getSubscriptions($offset);
            $allSubscribers = array_merge($allSubscribers, $fetchedSubscribers);
            $offset += 100;
        } while (!empty($fetchedSubscribers));

        $subscribers = [];

        foreach ($allSubscribers as $subscriber) {
            if (
                !empty($subscribers[$subscriber['name']])
                && $subscribers[$subscriber['name']]['tier'] < $subscriber['tier']
            ) {
                $subscribers[$subscriber['name']]['tier'] = $subscriber['tier'];
            } else {
                $subscribers[$subscriber['name']] = $subscriber;
            }
        }

        return view('subscribers', compact('subscribers'));
    }

    protected function getSubscriptions($offset = 0, $limit = 100, $direction = 'asc')
    {
        $options = [
            'headers' => [
                'Accept' => 'application/vnd.twitchtv.v5+json',
                'Client-ID' => env('TWITCH_KEY'),
                'Authorization' => 'OAuth ' . $this->token,
            ],
            'exceptions' => false,
        ];

        $client = new Client(['base_uri' => 'https://api.twitch.tv/kraken/']);
        $response = $client->request('GET',
            'channels/' . $this->channelId . '/subscriptions?offset=' . $offset . '&limit=' . $limit . 'direction=' . $direction,
            $options);

        if ($response->getStatusCode() !== 200) {
            abort('200', '', ['Location' => route('index')]);
        }

        $body = $response->getBody();
        $object = (json_decode((string)$body));
        $subscriptions = $object->subscriptions;
        $subscribers = [];

        foreach ($subscriptions as $subscription) {
            if (strtolower($subscription->user->name) != strtolower($this->channelName)) {
                $subscribers[] = [
                    'name' => $subscription->user->name,
                    'date' => $subscription->created_at,
                    'displayName' => $subscription->user->display_name,
                    'logo' => $subscription->user->logo,
                    'tier' => $subscription->sub_plan == "Prime" ? "Prime" : (int)$subscription->sub_plan / 1000,
                ];
            }
        }

        return $subscribers;
    }
}
