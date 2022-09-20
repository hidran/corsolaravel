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
        $sql = 'select * from albums WHERE 1=1 ';
        $where = [];
        if ($request->has('id')) {
            $where['id'] = $request->get('id');
            $sql .= ' AND ID=:id';
        }
        if ($request->has('album_name')) {
            $where['album_name'] = $request->get('album_name');
            $sql .= ' AND album_name=:album_name';
        }
        //dd($sql);
        $albums = DB::select($sql, $where);
        return view('albums', ['albums' => $albums]);

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        //
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
