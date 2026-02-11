<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Photo::with(['user', 'folder'])->orderBy('photo_date', 'desc')->orderBy('created_at', 'desc');

        $user = auth()->user();

        // Filter based on Role if not Global Admin
        if (!$user->isGlobalAdmin()) {
            $query->where('department', $user->getDepartment() ?? $user->role);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('photo_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('photo_date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $query->where('notes', 'like', '%' . $request->search . '%');
        }

        $photos = $query->get();
        $allDepartments = Photo::distinct()->pluck('department')->sort();
        $isGlobalAdmin = in_array($user->role, ['direktur', 'mr']);

        return view('photos.index', compact('photos', 'allDepartments', 'isGlobalAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $folderQuery = Folder::orderBy('name');

        if (!auth()->user()->isGlobalAdmin()) {
            $folderQuery->where('user_id', auth()->id());
        }

        $folders = $folderQuery->get();
        return view('photos.create', compact('folders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Increase memory limit for processing large iPhone photos
        ini_set('memory_limit', '1024M');

        $request->validate([
            'photos.*' => 'required|image|max:10240',
            'photo_date' => 'required|date',
            'location' => 'required|string',
            'department' => 'required|string',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($request->folder_id) {
            $targetFolder = Folder::find($request->folder_id);
            if (!auth()->user()->isGlobalAdmin() && $targetFolder->user_id !== auth()->id()) {
                return redirect()->back()
                    ->withInput()
                    ->with('swal_error', 'Anda tidak memiliki akses ke folder yang dipilih.');
            }
        }

        // Enforce department for non-global admins
        $department = $request->department;
        if (!auth()->user()->isGlobalAdmin()) {
            $department = auth()->user()->getDepartment() ?? $request->department;
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                try {
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
                        'department' => $department,
                        'notes' => $request->notes,
                        'folder_id' => $request->folder_id,
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Photo Upload Error: ' . $e->getMessage(), [
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'user_id' => auth()->id()
                    ]);

                    return redirect()->back()
                        ->withInput()
                        ->with('swal_error', 'Gagal memproses foto "' . $file->getClientOriginalName() . '". Pastikan format foto didukung (JPG/PNG/WebP) dan ukuran tidak terlalu besar.');
                }
            }
        }

        return redirect()->route('photos.index')->with('status', 'photos-uploaded');
    }

    public function timeline(Request $request)
    {
        $user = auth()->user();
        $query = Photo::with(['user', 'folder']);

        if (!$user->isGlobalAdmin()) {
            $query->where('department', $user->getDepartment() ?? $user->role);
        }

        // Date Jump Logic
        if ($request->filled('jump_date')) {
            // Check existence first
            $checkQuery = Photo::query();
            if (!$user->isGlobalAdmin()) {
                $checkQuery->where('department', $user->getDepartment() ?? $user->role);
            }
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


        // Previous Photo (Newer)
        $previous = Photo::query();
        if (!$user->isGlobalAdmin()) {
            $previous->where('department', $user->getDepartment() ?? $user->role);
        }
        $previous = $previous->where(function ($q) use ($photo) {
            $q->where('photo_date', '>', $photo->photo_date)
                ->orWhere(function ($sub) use ($photo) {
                    $sub->where('photo_date', $photo->photo_date)
                        ->where('created_at', '>', $photo->created_at);
                });
        })->orderBy('photo_date', 'asc')->orderBy('created_at', 'asc')->first();

        // Next Photo (Older)
        $next = Photo::query();
        if (!$user->isGlobalAdmin()) {
            $next->where('department', $user->getDepartment() ?? $user->role);
        }
        $next = $next->where(function ($q) use ($photo) {
            $q->where('photo_date', '<', $photo->photo_date)
                ->orWhere(function ($sub) use ($photo) {
                    $sub->where('photo_date', $photo->photo_date)
                        ->where('created_at', '<', $photo->created_at);
                });
        })->orderBy('photo_date', 'desc')->orderBy('created_at', 'desc')->first();

        $folderQuery = \App\Models\Folder::orderBy('name');
        if (!$user->isGlobalAdmin()) {
            $folderQuery->where('user_id', auth()->id());
        }
        $folders = $folderQuery->get();

        return view('photos.show', compact('photo', 'previous', 'next', 'folders'));
    }

    public function update(Request $request, Photo $photo)
    {
        // Authorization
        if ($photo->user_id !== auth()->id() && !in_array(auth()->user()->role, ['direktur', 'mr'])) {
            abort(403);
        }

        $request->validate([
            'photo_date' => 'required|date',
            'location' => 'required|string',
            'department' => 'required|string',
            'folder_id' => 'nullable|exists:folders,id',
            'notes' => 'nullable|string',
        ]);

        // Security check for folder
        if ($request->folder_id) {
            $targetFolder = \App\Models\Folder::find($request->folder_id);
            if (!auth()->user()->isGlobalAdmin() && $targetFolder->user_id !== auth()->id()) {
                return redirect()->back()->with('swal_error', 'Anda tidak memiliki akses ke folder yang dipilih.');
            }
        }

        // Enforce department for non-global admins
        $department = $request->department;
        if (!auth()->user()->isGlobalAdmin()) {
            $department = auth()->user()->getDepartment() ?? $request->department;
        }

        $photo->update([
            'photo_date' => $request->photo_date,
            'location' => $request->location,
            'department' => $department,
            'folder_id' => $request->folder_id,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('status', 'photo-updated');
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
