<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Requests\NoteUpdateRequest;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $search = $request->input('search');

    $notes = Note::where('user_id', auth()->id())
                 ->where('is_visible', true)
                 ->when($search, function ($query, $search) {
                     return $query->where(function ($q) use ($search) {
                         $q->where('title', 'like', "%{$search}%")
                           ->orWhere('content', 'like', "%{$search}%");
                     });
                 })
                 ->latest()
                 ->paginate(5);

    return view('notes.index', compact('notes', 'search'))
           ->with('i', (request()->input('page', 1) - 1) * 5);
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    Note::create([
        'title' => $request->title,
        'content' => $request->content,
        'user_id' => auth()->id(), // هنا الرابط
        'is_visible' => true
    ]);


    return redirect()->route('notes.index')
        ->with('success', 'Note created successfully.');
}

  /** 
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteUpdateRequest $request, Note $note)
    {
        $note->update($request->validated());

        return redirect()->route('notes.index')
            ->with('success', 'Note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', 'Note deleted successfully');
    }
    public function hide($id)
{
    $note = Note::findOrFail($id);
    $note->is_visible = false;
    $note->save();

    return response()->json(['message' => 'Note hidden successfully']);
}

}

