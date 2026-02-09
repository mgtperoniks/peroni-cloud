<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $query = Folder::withCount('photos');

        if (!in_array(auth()->user()->role, ['direktur', 'mr'])) {
            $query->where('user_id', auth()->id());
        }

        $folders = $query->orderBy('name')->get();

        return view('folders.index', compact('folders'));
    }

    public function create()
    {
        return view('folders.create');
    }

    public function show(Folder $folder)
    {
        // Role-based access control
        if (!in_array(auth()->user()->role, ['direktur', 'mr']) && $folder->user_id !== auth()->id()) {
            abort(403);
        }

        $photos = $folder->photos()->orderBy('photo_date', 'desc')->get();

        return view('folders.show', compact('folder', 'photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $name = $request->name;
        $originalName = $name;
        $counter = 1;

        // Check for duplicates for the current user (Hardening)
        while (Folder::where('name', $name)->where('user_id', auth()->id())->exists()) {
            $counter++;
            $name = $originalName . '_' . $counter;
        }

        $folder = Folder::create([
            'name' => $name,
            'user_id' => auth()->id(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'folder' => $folder
            ]);
        }

        if ($request->filled('redirect')) {
            return redirect($request->redirect)->with('status', 'folder-created');
        }

        return redirect()->route('folders.index')->with('status', 'folder-created');
    }

    public function update(Request $request, Folder $folder)
    {
        if ($folder->user_id !== auth()->id() && !in_array(auth()->user()->role, ['direktur', 'mr'])) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('status', 'folder-updated');
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== auth()->id() && !in_array(auth()->user()->role, ['direktur', 'mr'])) {
            abort(403);
        }

        // Folder will be deleted, associated photos will have folder_id set to NULL automatically 
        // if the migration uses onDelete('set null'). Let's double check migration later if needed,
        // but manually setting it is safer if we aren't sure.
        $folder->photos()->update(['folder_id' => null]);
        $folder->delete();

        return redirect()->route('folders.index')->with('status', 'folder-deleted');
    }
}
