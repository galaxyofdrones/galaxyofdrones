<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * @throws \Exception|\Throwable
     */
    public function testTitle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertTitle('Login - Galaxy of Drones Online');
        });
    }

    /**
     * @throws \Exception|\Throwable
     */
    public function testError()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('username_or_email', 'badusername')
                ->type('password', 'badpassword')
                ->uncheck('remember')
                ->press('Login')
                ->assertSee('These credentials do not match our records.');
        });
    }

    /**
     * @throws \Exception|\Throwable
     */
    public function testSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('username_or_email', 'koodilab')
                ->type('password', 'havefun')
                ->uncheck('remember')
                ->press('Login')
                ->assertDontSee('These credentials do not match our records.')
                ->assertPathIs('/start');
        });
    }
}
