<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Photo::with('user')->orderBy('photo_date', 'desc')->orderBy('created_at', 'desc');

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('date')) {
            $query->whereDate('photo_date', $request->date);
        }

        $photos = $query->get();

        return view('photos.index', compact('photos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('photos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|image|max:10240',
            'photo_date' => 'required|date',
            'location' => 'required|string',
            'department' => 'required|string',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                // Store Original High-Res
                $path = $file->store('photos', 'public');

                // Generate Thumbnail (Compressed)
                $thumbnailPath = 'thumbnails/' . basename($path);
                
                $imageManager = \Intervention\Image\Laravel\Facades\Image::read($file);
                
                // Resize to max 800px width/height maintaining aspect ratio
                // And encode as JPG with 60% quality to get roughly 300-400KB or less
                $thumbnail = $imageManager->scale(width: 800)->toJpeg(60);
                
                Storage::disk('public')->put($thumbnailPath, (string) $thumbnail);

                Photo::create([
                    'user_id' => auth()->id(),
                    'filename' => $path,
                    'thumbnail' => $thumbnailPath,
                    'photo_date' => $request->photo_date,
                    'location' => $request->location,
                    'department' => $request->department,
                    'notes' => $request->notes,
                ]);
            }
        }

        return redirect()->route('photos.index')->with('status', 'photos-uploaded');
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        return view('photos.show', compact('photo'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        Storage::disk('public')->delete($photo->filename);
        if ($photo->thumbnail) {
            Storage::disk('public')->delete($photo->thumbnail);
        }
        $photo->delete();

        return redirect()->route('photos.index')->with('status', 'photo-deleted');
    }
}
