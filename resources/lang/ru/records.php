<?php

return [
    'records'    => 'Записи',
    'no_records' => 'Записи отсутствуют',

    'attributes' => [
        'keyword' => 'Поисковая строка',
    ],

    'messages' => [
        'importing' => 'Импорт записей поставлен в очередь на обработку',
    ],

    'filters' => [
        'date_start' => 'Дата От',
        'date_end'   => 'Дата До',
    ],

    'import' => [
        'import' => 'Импорт',
        'file'   => 'Файл',
        'hint'   => 'Файл должен быть одним из :extensions расширений. Допустимый максимальный размер: :max_size Мбайт',
        'processing' => 'Обработано: :processed, всего: :total.',
        'finished'   => 'Обработано: :processed, всего: :total. Дата импорта: :finished',
        'error'      => 'Импорт завершился неудачей. Повторите позднее.',
    ]
];
