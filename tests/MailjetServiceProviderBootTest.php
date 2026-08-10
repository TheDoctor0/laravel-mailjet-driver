<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests;

use Orchestra\Testbench\TestCase;
use Mailjet\LaravelMailjet\MailjetServiceProvider;
use Mailjet\LaravelMailjet\Services\MailjetService;
use Symfony\Component\Mailer\Bridge\Mailjet\Transport\MailjetApiTransport;

class MailjetServiceProviderBootTest extends TestCase
{
    public function testMailjetSingletonResolvesToMailjetService(): void
    {
        $service = $this->app->make('Mailjet');

        $this->assertInstanceOf(MailjetService::class, $service);
        $this->assertSame($service, $this->app->make('Mailjet'));
    }

    public function testMailjetMailTransportIsRegistered(): void
    {
        $transport = $this->app['mail.manager']->mailer('mailjet')->getSymfonyTransport();

        $this->assertInstanceOf(MailjetApiTransport::class, $transport);
    }

    public function testProvidesMailjet(): void
    {
        $provider = new MailjetServiceProvider($this->app);

        $this->assertSame(['mailjet'], $provider->provides());
    }

    protected function getPackageProviders($app): array
    {
        return [MailjetServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('services.mailjet.key', 'ABC123456');
        $app['config']->set('services.mailjet.secret', 'DEF789101');
        $app['config']->set('services.mailjet.common.call', false);
        $app['config']->set('mail.mailers.mailjet', ['transport' => 'mailjet']);
    }
}
