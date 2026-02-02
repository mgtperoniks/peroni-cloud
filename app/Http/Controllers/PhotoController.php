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

        $user = auth()->user();

        // Role to Department Mapping
        $roleMap = [
            'adminppic' => 'PPIC',
            'adminqcfitting' => 'QC Fitting',
            'adminqcflange' => 'QC Flange',
            'qcinspektorpd' => 'QC Inspektor PD',
            'qcinspectorfl' => 'QC Inspector FL',
            'admink3' => 'K3',
            'sales' => 'Sales',
        ];

        // Filter based on Role if not Global Admin
        if (!in_array($user->role, ['direktur', 'mr'])) {
            if (isset($roleMap[$user->role])) {
                $query->where('department', $roleMap[$user->role]);
            } else {
                // strict fallback or allow seeing their own specific textual role
                $query->where('department', $user->role);
            }
        }

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

    public function timeline(Request $request)
    {
        $user = auth()->user();
        $query = Photo::query();

        // Role to Department Mapping (Same as index)
        $roleMap = [
            'adminppic' => 'PPIC',
            'adminqcfitting' => 'QC Fitting',
            'adminqcflange' => 'QC Flange',
            'qcinspektorpd' => 'QC Inspektor PD',
            'qcinspectorfl' => 'QC Inspector FL',
            'admink3' => 'K3',
            'sales' => 'Sales',
        ];

        $applyScope = function ($q) use ($user, $roleMap) {
            // Filter based on Role if not Global Admin
            if (!in_array($user->role, ['direktur', 'mr'])) {
                if (isset($roleMap[$user->role])) {
                    $q->where('department', $roleMap[$user->role]);
                } else {
                    $q->where('department', $user->role);
                }
            }
        };

        $applyScope($query);

        // Date Jump Logic
        if ($request->filled('jump_date')) {
            // Check existence first
            $checkQuery = Photo::query();
            $applyScope($checkQuery);
            $exists = $checkQuery->whereDate('photo_date', $request->jump_date)->exists();

            if ($exists) {
                $query->whereDate('photo_date', $request->jump_date);
            } else {
                session()->flash('swal_error', 'Tidak ada foto pada tanggal ' . \Carbon\Carbon::parse($request->jump_date)->format('d M Y'));
            }
        }

        $query->orderBy('photo_date', 'desc')->orderBy('created_at', 'desc');

        $groupedPhotos = $query->get()->groupBy(function ($photo) {
            return \Carbon\Carbon::parse($photo->photo_date)->format('F, Y');
        });

        return view('photos.timeline', compact('groupedPhotos'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        $user = auth()->user();

        // Helper to apply role constraints
        $applyScope = function ($query) use ($user) {
            $roleMap = [
                'adminppic' => 'PPIC',
                'adminqcfitting' => 'QC Fitting',
                'adminqcflange' => 'QC Flange',
                'qcinspektorpd' => 'QC Inspektor PD',
                'qcinspectorfl' => 'QC Inspector FL',
                'admink3' => 'K3',
                'sales' => 'Sales',
            ];

            if (!in_array($user->role, ['direktur', 'mr'])) {
                if (isset($roleMap[$user->role])) {
                    $query->where('department', $roleMap[$user->role]);
                } else {
                    $query->where('department', $user->role);
                }
            }
        };

        // Previous Photo (Newer)
        $previous = Photo::query();
        $applyScope($previous);
        $previous = $previous->where(function ($q) use ($photo) {
            $q->where('photo_date', '>', $photo->photo_date)
                ->orWhere(function ($sub) use ($photo) {
                    $sub->where('photo_date', $photo->photo_date)
                        ->where('created_at', '>', $photo->created_at);
                });
        })->orderBy('photo_date', 'asc')->orderBy('created_at', 'asc')->first();

        // Next Photo (Older)
        $next = Photo::query();
        $applyScope($next);
        $next = $next->where(function ($q) use ($photo) {
            $q->where('photo_date', '<', $photo->photo_date)
                ->orWhere(function ($sub) use ($photo) {
                    $sub->where('photo_date', $photo->photo_date)
                        ->where('created_at', '<', $photo->created_at);
                });
        })->orderBy('photo_date', 'desc')->orderBy('created_at', 'desc')->first();

        return view('photos.show', compact('photo', 'previous', 'next'));
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
