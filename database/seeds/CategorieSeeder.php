<?php

use Illuminate\Database\Seeder;
use App\Models\CategorieModel;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CategorieModel::Class)->create();
    }
}
