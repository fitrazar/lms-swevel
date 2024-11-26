<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $participants = Participant::query();


            return DataTables::of($participants)->make();
        }

        return view('admin.participant.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.participant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|numeric|unique:users,email',
            'name' => 'required|string',
            'gender' => 'required|in:Laki - Laki,Perempuan',
            'phone' => 'required|numeric',
            'photo' => 'nullable|image|max:4098',
            'password' => 'required',
        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('participant/photo', $validatedData['photo']);
        }

        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('participant');
        $user->save();
        $validatedData['user_id'] = $user->id;

        Participant::create($validatedData);

        return redirect()->route('dashboard.admin.participant.index')->with('success', 'Peserta Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        return view('admin.participant.edit', compact(var_name: 'participant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participant $participant)
    {
        $rules = [
            'email' => 'required|numeric|unique:users,email,' . $participant->id,
            'name' => 'required|string',
            'gender' => 'required|in:Laki - Laki,Perempuan',
            'phone' => 'required|numeric',
            'photo' => 'sometimes|image|max:4098',
        ];

        $validatedData = $request->validate($rules);
        $validatedData['photo'] = $request->oldImage;
        if ($request->file('photo')) {
            $path = 'participant/photo';
            if ($request->oldImage) {
                Storage::delete($path . '/' . $request->oldImage);
            }
            $validatedData['photo'] = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $photoPath = $request->file('photo')->storeAs('participant/photo', $validatedData['photo']);
        }


        Participant::findOrFail($participant->id)->update($validatedData);

        return redirect()->route('dashboard.admin.participant.index')->with('success', 'Peserta Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        if ($participant->photo) {
            Storage::delete('participant/photo/' . $participant->photo);
        }
        $participant->user->delete();
        Participant::destroy($participant->id);

        return redirect()->route('dashboard.admin.participant.index')->with('success', 'Peserta Berhasil Dihapus!');
    }
}
