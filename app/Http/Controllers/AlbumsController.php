<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Storage;

class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {


        $queryBuilder = Album::orderBy('id', 'DESC')
            ->withCount('photos');
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
        return view('albums.createalbum')->withAlbum(new Album());
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
        $data = $request->only(['album_name', 'description']);
        $album = new Album();
        $album->album_name = $data['album_name'];
        $album->description = $data['description'];
        $album->user_id = 1;
        $album->album_thumb = '/';
        $res = $album->save();
        if ($request->hasFile('album_thumb')) {

            $this->processFile($request, $album);
            $res = $album->save();
        }
        //$res =  Album::create($data);
        $messaggio = $res ? 'Album   ' . $data['album_name'] . ' Created' : 'Album ' . $data['album_name'] . ' was not crerated';
        session()->flash('message', $messaggio);
        return redirect()->route('albums.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album $album
     *
     * @return void
     */
    public function processFile(Request $request, Album $album): void
    {
        $file = $request->file('album_thumb');

        $filename = $album->id . '.' . $file->extension();
        $thumbnail = $file->storeAs(config('filesystems.album_thumbnail_dir'), $filename,
            ['disk' => 'public']);
        $album->album_thumb = $thumbnail;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Album $album
     *
     * @return \App\Models\Album $album
     */
    public function show(Album $album)
    {
        return $album;
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
    public function update(Request $request, Album $album): RedirectResponse
    {
        $data = $request->only(['album_name', 'description']);
        $album->album_name = $data['album_name'];
        $album->description = $data['description'];
        if ($request->hasFile('album_thumb')) {

            $this->processFile($request, $album);
        }
        $res = $album->save();
        $messaggio = $res ? 'Album   ' . $album->album_name . ' Updated' : 'Album ' . $album->album_name . ' was not updated';
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
    public function destroy(Album $album)
    {
        $thumbnail = $album->album_thumb;
        $res = $album->delete();
        if ($thumbnail) {
            Storage::delete($thumbnail);
        }
        return $res;
    }

    public function getImages(Album $album)
    {
        return Photo::wherealbumId($album->id)->get();

    }
}
