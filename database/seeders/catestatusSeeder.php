<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Catestatus;

class catestatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            
        $catestatus = new Catestatus;
        $catestatus-> ecodEstatus = '01ae3cd3-144c-46a3-99aa-bb9174761b37';
        $catestatus-> tNombre = 'proceso';
        $catestatus->save();

        $catestatus1 = new Catestatus;
        $catestatus1-> ecodEstatus = '2660376e-dbf8-44c1-b69f-b2554e3e5d4c';
        $catestatus1-> tNombre = 'Activo';
        $catestatus1->save();

        $catestatus2 = new Catestatus;
        $catestatus2-> ecodEstatus = '5bf018a2-07c0-497e-b376-94531e337dad';
        $catestatus2-> tNombre = 'Cancelado';
        $catestatus2->save();

        $catestatus3 = new Catestatus;
        $catestatus3-> ecodEstatus = '93a484c5-ce42-435e-98ac-12bc215d95b5';
        $catestatus3-> tNombre = 'Terminado';
        $catestatus3->save();

        $catestatus4 = new Catestatus;
        $catestatus4-> ecodEstatus = 'fa6cc9a2-f221-4e27-b575-1fac2698d27a';
        $catestatus4-> tNombre = 'Eliminado';
        $catestatus4->save();
    }
}