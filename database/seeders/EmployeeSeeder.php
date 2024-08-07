<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'nip' => '198012311994031002',
                'name' => 'Yani',
                'position' => 'Kepala Sekolah Bidang Kurikulum',
                'phone' => '08123456792',
                'email' => 'yani@smkn11bdg.sch.id',
            ],
            [
                'nip' => '197412311994031001',
                'name' => 'Sutarsa',
                'position' => 'Wakil Kepala Sekolah Bidang Sarana Prasarana',
                'phone' => '08123456789',
                'email' => 'sutarsa@smkn11bdg.sch.id',
            ],
            [
                'nip' => '197806311994031003',
                'name' => 'Toni Kusmara',
                'position' => 'Staff Sarana Prasarana',
                'phone' => '08123456781',
                'email' => 'toni.kusmara@smkn11bdg.sch.id',
            ],
            [
                'nip' => '198212311994031004',
                'name' => 'Ani Nuraeni',
                'position' => 'Ketua Progam keahlian RPL',
                'phone' => '08123456782',
                'email' => 'zim.zim@smkn11bdg.sch.id',
            ],
            [
                'nip' => '197412311994031005',
                'name' => 'Zim zim',
                'position' => 'Ketua Progam keahlian DKV',
                'phone' => '08123456783',
                'email' => 'ani.nuraeni@smkn11bdg.sch.id',
            ],
        ];

        Employee::insert($employees);
        
    }
}
