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
         shell_exec("k2g ".public_path()."/map/raw.kml ".public_path()."/map");
         $features = json_decode(file_get_contents(public_path()."/map/raw.geojson"), true)["features"];
         foreach($features as $feature){
             $coords = $feature["geometry"]["coordinates"][0];
             for($i = 0; $i < count($coords); $i++){
                 $coords[$i] = array_reverse(array_slice($coords[$i], 0, 2));
             }

             $neighbourhood = new Neighbourhood();
             $neighbourhood->fill(["name" => $feature["properties"]["name"], "coords" => json_encode($coords)]);
             $neighbourhood->save();
         }
    }
}
