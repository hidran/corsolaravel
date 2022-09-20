<?php

namespace App\Http\Controllers;

use App\Models\Album;
use DB;
use Illuminate\Http\Request;

class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $queryBuilder = DB::table('albums')->orderBy('id', 'DESC');
        if ($request->has('id')) {
            $queryBuilder->where('id', '=', $request->input('id'));
        }
        if ($request->has('album_name')) {
            $queryBuilder->where('album_name', 'like', $request->input('album_name') . '%');
        }
        $albums = $queryBuilder->get();
        return view('albums.albums', ['albums' => $albums]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('albums.createalbum');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['album_name', 'description']);
        $data['user_id'] = 1;
        $data['album_thumb'] = '';
        $query = 'INSERT INTO albums (album_name, description, user_id,album_thumb)  values (:album_name, :description, :user_id,:album_thumb)';
        $res = DB::insert($query, $data);
        $message = 'Album ' . $data['album_name'];
        $message .= $res ? ' created' : ' not created';
        session()->flash('message', $message);
        return redirect()->route('albums.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        /*
      $sql = 'select * from albums  where id=:id';
      $albumEdit = Db::select($sql, ['id' => $album->id]);
*/
        return view('albums.editalbum')->withAlbum($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $album)
    {
        $data = $request->only(['album_name', 'description']);
        $data['id'] = $album;
        $query = 'UPDATE albums set album_name=:album_name, description=:description where id=:id';
        $res = Db::update($query, $data);
        $message = 'Album con id= ' . $album;
        $message .= $res ? ' aggionarato' : ' Non aggiornato';
        session()->flash('message', $message);
        return redirect()->route('albums.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Album $album
     *
     * @return int
     */
    public function destroy(int $album)
    {
        $sql = 'DELETE FROM albums WHERE id=:id';
        return DB::delete($sql, ['id' => $album]);

    }

    public function delete(int $album)
    {
        $sql = 'DELETE FROM albums WHERE id=:id';
        return DB::delete($sql, ['id' => $album]);

    }
}
