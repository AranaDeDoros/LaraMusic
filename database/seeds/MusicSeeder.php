<?php

use Illuminate\Database\Seeder;
use App\Artist;
use App\Album;
//use DB;
class MusicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Album::class, 20)->create();
      //  factory(Artist::class,20)->create();

         factory(Artist::class, 20)->create()->each(function($artista){
            $libros = rand(3,10);
            for ($i=0; $i < $libros; $i++) { 
                $artista->albums()->attach(Album::all()->random()->id);
            }
            
        });

               

        $pivote = DB::table('album_artist')->select('listened')->get();
        $db = DB::table('album_artist');
        $nregistros = $db->count();

        foreach($pivote as $ele){
             $status = rand(0,1);
             $id = rand(1,$nregistros);
             //echo $id;
            $db
            ->where('id','=',(string)$id)
            ->update(['listened' => (string)$status ])
            ;
        }
        
       
    }
}
