<?php

use Illuminate\Database\Seeder;
use App\Service;
class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Questa impostazione crea a casa sponsor faker
        // factory(App\Service::class, 5)->create();
        // factory(App\Flat::class)->create()->each(function($a) {
        //   $a->services()->attach(App\Service::all()->random(1));
        // });
        // questa imopstazione crea sponsor statici
        $services = config('service.service_db');
        foreach ($services as $service) {
           $new_service = new Service();

           $new_service->fill($service);

           $new_service->save();
       }
    }
}
