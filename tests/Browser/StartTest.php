<?php

namespace Tests\Browser;

use Koodilab\Models\Planet;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StartTest extends DuskTestCase
{
    /**
     * @throws \Exception|\Throwable
     */
    public function testFirstPlanet()
    {
        Planet::first()->update([
            'resource_id' => 1,
            'size' => Planet::SIZE_SMALL,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('username_or_email', 'koodilab')
                ->type('password', 'havefun')
                ->uncheck('remember')
                ->press('Login')
                ->assertPathIs('/start')
                ->clickLink('Get my first planet', 'button')
                ->assertPathIs('/');
        });
    }

    /**
     * @throws \Exception|\Throwable
     */
    public function testSidebar()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('.fa-bars')
                ->assertVisible('.sidebar-content')
                ->assertSeeIn('.sidebar-content', 'Mining rate')
                ->assertSeeIn('.sidebar-content', 'Production rate')
                ->assertSeeIn('.sidebar-content', 'Incoming')
                ->assertSeeIn('.sidebar-content', 'Outgoing')
                ->assertSeeIn('.sidebar-content', 'Construction')
                ->assertSeeIn('.sidebar-content', 'Upgrade')
                ->assertSeeIn('.sidebar-content', 'Training')
                ->assertVisible('.resource-1')
                ->assertVisible('.resource-2')
                ->assertVisible('.resource-3')
                ->assertVisible('.resource-4')
                ->assertVisible('.resource-5')
                ->assertVisible('.resource-6')
                ->assertVisible('.resource-7')
                ->assertVisible('.solarion')
                ->assertVisible('.unit-1')
                ->assertVisible('.unit-2')
                ->assertVisible('.unit-3')
                ->assertVisible('.unit-4')
                ->assertVisible('.unit-5')
                ->assertVisible('.unit-6')
                ->assertVisible('.unit-7')
                ->assertVisible('.unit-8');
        });
    }
}
