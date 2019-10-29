<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Artist;
use App\Album;
use DB;

class MusicController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if($request->ajax()){
            $records = $this->getAllRecords()->distinct()->get();
            return $records->toJson();
        }else{
            return view('home');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $artist = $request->artist;
        $country = $request->country;

        $album = $request->album;
        $year = $request->year;
        $ntracks = $request->ntracks;
        $length = $request->length;

        $status = (int) $request->status;

        $newArtist = new Artist();
        $newArtist->name = $artist;
        $newArtist->country = $country;
        $newArtist->save();

        $newAlbum = new Album();
        $newAlbum->name= $album;
        $newAlbum->year = $year;
        $newAlbum->ntracks = (int) $ntracks;
        $newAlbum->length = (int) $length;
        $newAlbum->save();


        if($newArtist !=null && $newAlbum!=null){
            $newArtistID = Artist::orderby('id','desc')->first();
            $newAlbumID = Album::orderby('id','desc')->first(); 
            DB::table('album_artist')
            ->insert([
                'artist_id' => $newArtistID->id,
                'album_id'=> $newAlbumID->id,
                'listened' => $status
            ]);   


          $newRecord = $this->getAllRecords()
            ->latest('IDPIVOTE', 'desc')
            ->first();

            return response()->json(['data'=>$newRecord]);

        }else{
            return null;
        }   

        
            


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id_pivot = $id;
        $artista = $request->artist;
        $album = $request->album;
        $year = $request->year;
        $country = $request->country;
        $ntracks = $request->ntracks;
        $length = $request->length;
        $status = $request->status;
        $id_artist = $request->id_artist;
        $id_album = $request->id_album;

        
        $updatedArtist = Artist::find($id_artist);
        $updatedArtist->name = $artista;
        $updatedArtist->country = $country;
        $updateArt = $updatedArtist->save();

        $updatedAlbum = Album::find($id_album);
        $updatedAlbum->name = $album;
        $updatedAlbum->year = $year;
        $updatedAlbum->ntracks = (int) $ntracks;
        $updatedAlbum->length = (int) $length;
        $updateAlb = $updatedAlbum->save();

        if($updateArt && $updateAlb){
            $updatePiv = DB::table('album_artist')
                         ->where('id','=',$id_pivot)
                         ->update([
                                'artist_id'=>(int) $id_artist,
                                'album_id'=>(int) $id_album,
                                'listened'=>(int) $status
                         ]);
            //retornamos el pivote nuevo
         $updatedRecord = $this->getAllRecords()
            ->get()
            ->where('IDPIVOTE','=',$id_pivot);

            return response()->json(['data'=>$updatedRecord]);
             
        }
        

        


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = explode('&', $id);
        return  DB::table('album_artist')
        ->where('artist_id','=',$ids[0])
        ->where('album_id','=',$ids[1])
        ->where('id','=',$ids[2] )
        ->delete();
        
    }

    protected function getAllRecords(){
         $records = DB::table('album_artist')
            ->join('artists','artists.id','=','album_artist.artist_id')
            ->join('albums', 'albums.id','=', 'album_artist.album_id')
            ->select('albums.name as ALBUM','albums.year as ANIO',
                     'artists.country as COUNTRY', 'albums.ntracks as NTRACKS','albums.length as LENGTH',
                     'artists.name as ARTISTA','album_artist.listened as STATUS',
                     'albums.id as IDALBUM','artists.id as IDARTISTA', 'album_artist.id as IDPIVOTE')
            ;
        return $records;
    }
}
