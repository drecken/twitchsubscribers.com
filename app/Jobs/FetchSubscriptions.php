<?php

namespace App\Jobs;

use App\Models\User;
use App\Twitch\Helix;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SynchronizeSubscriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected array $users;
    protected Helix $helix;
    protected Collection $subscriptions;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->helix = new Helix(config('services.twitch.client_id'), $this->user->twitch_token);
        $this->subscriptions = collect($this->helix->getSubscriptions($this->user->id));
        $this->synchronizeUsers();
        $this->synchronizeSubscriptions();
    }

    protected function synchronizeUsers()
    {
        $users = collect($this->helix->getUsers($this->subscriptions->pluck('user_id')->toArray()));
        foreach ($users->chunk(100) as $users) {
            DB::table('users')->upsert(
                $users->map(
                    function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->display_name,
                            'avatar' => $user->profile_image_url,
                            'updated_at' => Carbon::now(),
                        ];
                    }
                )->toArray(),
                ['id'],
                ['name', 'avatar', 'updated_at']
            );
        }
    }

    protected function synchronizeSubscriptions()
    {
        foreach ($this->subscriptions->chunk(100) as $subscriptions) {
            DB::table('subscriptions')->upsert(
                $subscriptions->map(
                    function ($subscription) {
                        return [
                            'broadcaster_id' => $subscription->broadcaster_id,
                            'subscriber_id' => $subscription->user_id,
                            'gifter_id' => empty($subscription->gifter_id) ? null : $subscription->gifter_id,
                            'tier' => $subscription->tier,
                        ];
                    }
                )->toArray(),
                ['broadcaster_id', 'subscriber_id'],
                ['gifter_id', 'tier']
            );
        }
    }
}
