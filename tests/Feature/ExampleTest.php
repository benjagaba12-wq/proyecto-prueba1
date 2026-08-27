<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * '/' redirige a '/proyectos' (ver routes/web.php), por lo que ya no
     * responde 200 directamente sino con un redirect.
     */
    public function test_the_application_redirects_home_to_proyectos(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/proyectos');
    }
}
