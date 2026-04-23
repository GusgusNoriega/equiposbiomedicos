<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_root_path_renders_the_public_home_page(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Equipos biomedicos y servicios', false)
            ->assertSee(route('login'), false);
    }
}
