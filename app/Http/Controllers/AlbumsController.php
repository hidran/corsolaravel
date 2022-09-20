<?php

namespace App\Http\Controllers;

use App\Models\Album;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
       

        $queryBuilder = Album::orderBy('id', 'DESC');
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = request()->only(['album_name', 'description']);
        $data['user_id'] = 1;
        // da aggiungere se c'è già la colonna album_thumb nella tabella
        $data['album_thumb'] = '/';


        $res = DB::table('albums')->insert($data);
        $messaggio = $res ? 'Album   ' . $data['album_name'] . ' Created' : 'Album ' . $data['album_name'] . ' was not crerated';
        session()->flash('message', $messaggio);
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
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->only(['album_name', 'description']);
        $res = DB::table('albums')->where('id', $id)->update($data);
        $messaggio = $res ? 'Album   ' . $id . ' Updated' : 'Album ' . $id . ' was not updated';
        session()->flash('message', $messaggio);
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
        // $res = DB::table('albums')->delete($album);
        $res = DB::table('albums')->where('id', $album)->delete();
        return $res;

    }

    public function delete(int $album)
    {
        return $this->destroy($album);

    }
}
