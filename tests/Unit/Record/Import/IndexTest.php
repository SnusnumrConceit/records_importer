<?php

namespace Tests\Unit\Record\Import;

use Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * Маршрут к форме
     *
     * @return string
     */
    public function getIndexRoute() : string
    {
        return route('records.import.index');
    }

    /**
     * Тест, что любой может увидеть форму импорта records
     */
    public function testAnyoneCanSeeRecordsImportForm()
    {
        $response = $this->get($this->getIndexRoute());

        $response->assertSuccessful();
        $response->assertViewIs('records.import.index');
    }
}
