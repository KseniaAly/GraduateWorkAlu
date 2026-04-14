<?php

namespace Database\Seeders;

use App\Models\QuestionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuestionCategory::truncate();
        DB::table('question_categories')->insert([
            [
                'title' => 'Закрытый вопрос',
                'description' => 'Выберите один правильный ответ',
            ],
            [
                'title' => 'Вопрос с несколькими вариантами',
                'description' => 'Выберите несколько вариантов ответа',
            ],
            [
                'title' => 'Открытый вопрос',
                'description' => 'Дайте развернутый ответ на вопрос',
            ],
            [
                'title' => 'Задание',
                'description' => 'Прикрепите файл с выполненным заданием',
            ]
        ]);
//        $categories = [
//            [
//                'title' => 'Закрытый вопрос',
//                'description' => 'Выберите один правильный ответ',
//            ],
//            [
//                'title' => 'Вопрос с несколькими вариантами',
//                'description' => 'Выберите несколько вариантов ответа',
//            ],
//            [
//                'title' => 'Открытый вопрос',
//                'description' => 'Дайте развернутый ответ на вопрос',
//            ],
//            [
//                'title' => 'Задание',
//                'description' => 'Прикрепите файл с выполненным заданием',
//            ]
//        ];
//        foreach ($categories as $category) {
//            QuestionCategory::create($category);
//        }
    }
}
