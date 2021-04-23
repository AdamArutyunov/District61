<?php

namespace App\Console\Commands;

use App\Models\Neighbourhood;
use Illuminate\Console\Command;

class generateDistricts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateDistricts';

    /**
     * The console command description.
     *
         * @var string
         */
        protected $description = 'Command description';

        /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $xml = simplexml_load_file(public_path()."/map.kml");
        foreach($xml->Document->Placemark as $pm){
            $neighbourhood = new Neighbourhood();
            $c = "N047.14.14.451,E039.43.08.672;N047.14.39.148,E039.43.07.103;N047.14.39.165,E039.43.07.137;N047.14.40.023,E039.42.46.884;N047.14.15.424,E039.42.48.478;N047.14.11.763,E039.42.50.759;N047.14.13.283,E039.42.59.424;N047.14.14.451,E039.43.08.672;";
            $neighbourhood->fill(["name" => $pm->name, "coords" => $c]);
            $neighbourhood->save();
        }
    }
}
