<?php

use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    const COLUMNS_INDEXES = ['name', 'date'];

    /**
     * Запуск миграции создания таблицы records
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->timestamp('date')->index()->comment('дата из импортируемого файла');
            $table->timestamp('imported_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Откат миграции создания таблицы records
     *
     * @return void
     */
    public function down()
    {
        Schema::table('records', function (Blueprint $table) {
            foreach (static::COLUMNS_INDEXES as $index) {
                $table->dropIndex([$index]);
            }
        });

        Schema::dropIfExists('records');
    }
}
