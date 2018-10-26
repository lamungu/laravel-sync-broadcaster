<?php

namespace Lamungu\LaravelSyncBroadcaster\Broadcasters;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Broadcasting\BroadcastException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Sync\V1\Service\SyncStream\StreamMessageInstance;
use Twilio\Rest\Sync\V1\ServiceContext;

class SyncBroadcaster extends Broadcaster
{
    /**
     * The Sync instance.
     *
     * @var ServiceContext
     */
    protected $sync;

    /**
     * Create a new broadcaster instance.
     *
     * @param  ServiceContext  $sync
     * @return void
     */
    public function __construct(ServiceContext $sync)
    {
        $this->sync = $sync;
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function auth($request)
    {
        if (Str::startsWith($request->channel_name, ['private-', 'presence-']) &&
            ! $request->user()) {
            throw new AccessDeniedHttpException();
        }

        $channelName = Str::startsWith($request->channel_name, 'private-')
            ? Str::replaceFirst('private-', '', $request->channel_name)
            : Str::replaceFirst('presence-', '', $request->channel_name);

        return parent::verifyUserCanAccessChannel(
            $request, $channelName
        );
    }

    /**
     * Return the valid authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        if (Str::startsWith($request->channel_name, 'private')) {
            return ['success' => true];
        }

        return $result;
    }

    /**
     * Broadcast the given event.
     *
     * @param  array  $channels
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $socket = Arr::pull($payload, 'socket');
        foreach($this->formatChannels($channels) as $channel) {
            try {
                $response = $this->sync
                    ->syncStreams($channel)
                    ->streamMessages
                    ->create([
                        'type' => $event,
                        'payload' => $payload,
                        'identity' => $socket,
                    ]);
                if ($response instanceof StreamMessageInstance) {
                    continue;
                }
            } catch (TwilioException $e) {
                throw new BroadcastException('Failed to broadcast to Sync: ' . $e->getMessage());
            }
        }
    }

    /**
     * Get the Sync SDK instance.
     *
     * @return ServiceContext
     */
    public function getSync()
    {
        return $this->sync;
    }
}
