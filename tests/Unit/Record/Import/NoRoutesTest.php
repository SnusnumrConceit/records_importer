<?php

namespace Tests\Unit\Record\Import;

use Tests\Unit\BaseNoRoutesTest;

class NoRoutesTest extends BaseNoRoutesTest
{
    protected $excludedRoutes = [
        'records.import.create',
        'records.import.show',
        'records.import.edit',
        'records.import.update',
        'records.import.destroy',
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
