<?php

namespace Tests\Unit\Record;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Str;

class IndexTest extends TestCase
{
    /**
     * Получить маршрут к списку records
     *
     * @param array|null $bindings
     * @return string
     */
    public function getIndexRoute(array $bindings = [])
    {
        $route = route('records.index', $bindings);

        return $route;
    }

    /**
     * Проверки, что запрос выполнен успешно
     *
     * @param \Illuminate\Testing\TestResponse $response
     */
    protected function assertRecordIndexSuccessful($response)
    {
        $response->assertSuccessful();

        $response->assertViewIs('records.index');
        $response->assertViewHas('groups');
    }

    /**
     * Проверки, что запрос завершился неудачей
     *
     * @param \Illuminate\Testing\TestResponse $response
     * @param string|array
     */
    protected function assertFailed($response, $errors)
    {
       $response->assertRedirect($this->getIndexRoute());

       if (is_string($errors)) {
           $errors = [$errors];
       }

       $response->assertSessionHasErrors($errors);
    }

    /**
     * Тест, что любой пользователь может увидеть записи
     */
    public function testAnyoneCanSeeRecords()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute());

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь может произвести поиск с пустой Поисковой строкой
     */
    public function testAnyoneCanSearchRecordsWhenKeywordIsEmpty()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'keyword' => ''
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если Поисковая строка не является строкой
     */
    public function testAnyoneCannotSearchRecordsWhenKeywordIsNotString()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'keyword' => false
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если Поисковая строка превышает 255 символов
     */
    public function testAnyoneCannotSearchRecordsWhenKeywordIsMoreMaxLimit()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute(['keyword' => Str::random('256')]));

        $this->assertFailed($response, 'keyword');
    }

    /**
     * Тест, что любой пользователь может произвести поиск с пустой Датой До
     */
    public function testAnyoneCanSearchRecordsWhenDateStartIsEmpty()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_start' => ''
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь может произвести поиск если Дата До указана в формате Y-m-d
     */
    public function testAnyoneCanSearchRecordsWhenDateStartHasCorrectFormat()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_start' => now()->format('Y-m-d')
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если Дата До имеет формат отличный от Y-m-d
     */
    public function testAnyoneCannotSearchRecordsWhenDateStartHasWrongFormat()
    {
        foreach ([Str::random('4'), now()->format('d.m.Y')] as $value) {
            $response = $this->from($this->getIndexRoute())
                ->get($this->getIndexRoute([
                    'date_start' => $value
                ]));

            $this->assertFailed($response, 'date_start');
        }
    }

    /**
     * Тест, что любой пользователь может произвести поиск если указана Дата До, а Дата После не указана
     */
    public function testAnyoneCanSearchRecordsWhenDateStartIsValidAndDateEndIsEmpty()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_start' => now()->format('Y-m-d'),
                'date_end'   => '',
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если указана Дата До больше Даты После
     */
    public function testAnyoneCannotSearchRecordsWhenDateStartIsMoreThanDateEndAfter()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_start' => now()->format('Y-m-d'),
                'date_end'   => Carbon::yesterday()->format('Y-m-d'),
            ]));

        $this->assertFailed($response, ['date_start', 'date_end']);
    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если Дата До в неверном формате, а Дата После в правильном
     */
    public function testAnyoneCannotSearchRecordsWhenDateStartIsInvalidAndDateEndIsValid()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_start' => 'Invalid date',
                'date_end'   => Carbon::yesterday()->format('Y-m-d'),
            ]));

        $this->assertFailed($response, ['date_start', 'date_end']);
    }

        /**
     * Тест, что любой пользователь может произвести поиск с пустой Датой После
     */
    public function testAnyoneCanSearchRecordsWhenDateEndIsEmpty()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_end' => ''
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь может произвести поиск если Дата После указана в формате Y-m-d
     */
    public function testAnyoneCanSearchRecordsWhenDateEndHasCorrectFormat()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_end' => now()->format('Y-m-d')
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если Дата После имеет формат отличный от Y-m-d
     */
    public function testAnyoneCannotSearchRecordsWhenDateEndHasWrongFormat()
    {
        foreach ([Str::random('4'), now()->format('d.m.Y')] as $value) {
            $response = $this->from($this->getIndexRoute())
                ->get($this->getIndexRoute([
                    'date_end' => $value
                ]));

            $this->assertFailed($response, 'date_end');
        }

    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если Дата После старше сегодняшней даты
     */
    public function testAnyoneCannotSearchRecordsWhenDateEndIsAfterTomorrow()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_end' => Carbon::tomorrow()->format('Y-m-d')
            ]));

        $this->assertFailed($response, 'date_end');
    }

    /**
     * Тест, что любой пользователь может произвести поиск если указана Дата После, а Дата До не указана
     */
    public function testAnyoneCanSearchRecordsWhenDateEndIsValidAndDateStartIsEmpty()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_start' => '',
                'date_end'   => now()->format('Y-m-d'),
            ]));

        $this->assertRecordIndexSuccessful($response);
    }

    /**
     * Тест, что любой пользователь НЕ может произвести поиск если Дата До указана в правильном формате, а Дата После в неверном
     */
    public function testAnyoneCannotSearchRecordsWhenDateStartIsValidAndDateEndIsInvalid()
    {
        $response = $this->from($this->getIndexRoute())
            ->get($this->getIndexRoute([
                'date_start' => Carbon::yesterday()->format('Y-m-d'),
                'date_end'   => 'Invalid date',
            ]));

        $this->assertFailed($response, ['date_start', 'date_end']);
    }
}
