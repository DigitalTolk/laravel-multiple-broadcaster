<?php

declare(strict_types=1);

namespace DigitalTolk\MultipleBroadcaster\Tests;

use DigitalTolk\MultipleBroadcaster\MultipleBroadcaster;
use DigitalTolk\MultipleBroadcaster\ServiceProvider;
use Illuminate\Contracts\Broadcasting\Factory as BroadCastingFactory;
use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        config([
            'broadcasting.connections.multiple' => [
                'driver' => 'multiple',
                'connections' => [
                    'log',
                ],
            ],
        ]);

        $manager = $this->app->make(BroadCastingFactory::class);

        $connection = $manager->connection('multiple');

        self::assertInstanceOf(MultipleBroadcaster::class, $connection);
    }

    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
