<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

abstract class BaseNoRoutesTest extends TestCase
{
    /**
     * Проверка, что маршруты отсутствуют
     */
    public function testThatRoutesDoesNotExists()
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $this->assertFalse(in_array($route->getName(), $this->getExcludedRules()));
        }
    }

    /**
     * Маршруты исключенные из правил
     *
     * @return array
     */
    abstract protected function getExcludedRules() : array;
}
