<?php

namespace DigitalTolk\MultipleBroadcaster;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Exception;

class MultipleBroadcaster extends Broadcaster
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var bool
     */
    protected $safe = true;

    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @var BroadcastManager
     */
    protected $broadcastManager;

    /**
     * MultipleBroadcaster constructor.
     *
     * @param array $config
     * @param BroadcastManager $broadcastManager
     */
    public function __construct(array $config, BroadcastManager $broadcastManager)
    {
        $this->config = $config;
        $this->connections = Arr::get($config, 'connections', []);
        $this->safe = Arr::get($config, 'safe', $this->safe);
        $this->broadcastManager = $broadcastManager;
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function auth($request)
    {
        $returnValue = null;

        foreach ($this->connections as $connection) {
            $returnValue = $returnValue ?? $this->broadcastManager->connection($connection)->auth($request);
        }

        return $returnValue;
    }

    /**
     * Return the valid authentication response.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        $returnValue = null;

        foreach ($this->connections as $connection) {
            $returnValue = $returnValue ?? $this->broadcastManager->connection($connection)->validAuthenticationResponse($request, $result);
        }

        return $returnValue;
    }

    /**
     * Broadcast the given event.
     *
     * @param array $channels
     * @param string $event
     * @param array $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        foreach ($this->connections as $connection) {
            if ($this->safe) {
                try {
                    $this->broadcastManager->connection($connection)->broadcast($channels, $event, $payload);
                } catch (Exception $exception) {
                    Log::error('Error broadcasting to ' . $connection, [
                        'message' => $exception->getMessage()
                    ]);
                }
            } else {
                $this->broadcastManager->connection($connection)->broadcast($channels, $event, $payload);
            }
        }
    }
}
