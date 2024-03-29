<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Models\Photo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Storage;

class AlbumsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Album::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $albumsPerPage = config('filesystems.albums_per_page');
        $queryBuilder = Album::orderBy('id', 'DESC')
            ->withCount('photos');
        $queryBuilder->where('user_id', Auth::id());
        if ($request->has('id')) {
            $queryBuilder->where('id', '=', $request->input('id'));
        }
        if ($request->has('album_name')) {
            $queryBuilder->where('album_name', 'like', $request->input('album_name') . '%');
        }
        $albums = $queryBuilder->paginate($albumsPerPage);
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
     * @param AlbumRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AlbumRequest $request): RedirectResponse
    {
        $data = $request->safe(['album_name', 'description']);
        $album = new Album();
        $album->user_id = Auth::id();
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
        $thumbnail = $file->storeAs(
            config('filesystems.album_thumbnail_dir'),
            $filename,
            ['disk' => 'public']
        );
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
        if (Auth::id() === $album->user_id) {
            return $album;
        }
        abort(404);
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
        $this->authorize($album);
        return view('albums.editalbum')->withAlbum($album);
    }


    /**
     * @param AlbumRequest $request
     * @param Album $album
     * @return RedirectResponse
     */
    public function update(AlbumRequest $request, Album $album): RedirectResponse
    {
        $this->authorize($album);
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
        if (Gate::denies('manage-album', $album)) {
            return false;
        }
        $thumbnail = $album->album_thumb;
        $res = $album->delete();
        if ($thumbnail) {
            Storage::delete($thumbnail);
        }
        if (request()->ajax()) {
            return $res;
        }
        return redirect()->back();
    }

    public function getImages(Album $album)
    {
        $imgPerPage = config('filesystems.img_per_page');
        $images = Photo::wherealbumId($album->id)->latest()->paginate($imgPerPage);
        return view('images.albumimages', compact('album', 'images'));
    }
}
