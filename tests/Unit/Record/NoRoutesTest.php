<?php

namespace Tests\Unit\Record;

use Tests\Unit\BaseNoRoutesTest;

class NoRoutesTest extends BaseNoRoutesTest
{
    protected $excludedRoutes = [
        'records.store',
        'records.create',
        'records.show',
        'records.edit',
        'records.update',
        'records.destroy',
    ];

    /**
     * Маршруты исключенные из правил
     *
     * @return array
     */
    protected function getExcludedRules() : array
    {
        return $this->excludedRoutes;
    }
}
