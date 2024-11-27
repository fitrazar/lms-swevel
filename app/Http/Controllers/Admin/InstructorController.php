<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $instructors = Instructor::all();


            return DataTables::of($instructors)->make();
        }

        return view('admin.instructor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.instructor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:75',
            'gender' => 'required|in:Laki - Laki,Perempuan',
            'phone' => 'required|numeric',
            'photo' => 'nullable|image|max:4098',
            'password' => 'required|min:8',
        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('instructor/photo', $validatedData['photo']);
        }

        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('instructor');
        $user->save();
        $validatedData['user_id'] = $user->id;

        Instructor::create([
            'user_id' => $validatedData['user_id'],
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'phone' => $validatedData['phone'],
            'photo' => $validatedData['photo'],
        ]);

        return redirect()->route('dashboard.admin.instructor.index')->with('success', 'Mentor Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        return view('admin.instructor.edit', compact(var_name: 'instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $rules = [
            'name' => 'required|string',
            'gender' => 'required|in:Laki - Laki,Perempuan',
            'phone' => 'required|numeric',
            'photo' => 'sometimes|image|max:4098',
        ];

        $validatedData = $request->validate($rules);
        $validatedData['photo'] = $request->oldImage;
        if ($request->file('photo')) {
            $path = 'instructor/photo';
            if ($request->oldImage) {
                Storage::delete($path . '/' . $request->oldImage);
            }
            $validatedData['photo'] = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs($path, $validatedData['photo']);
        }


        Instructor::findOrFail($instructor->id)->update([
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'phone' => $validatedData['phone'],
            'photo' => $validatedData['photo'],
        ]);

        return redirect()->route('dashboard.admin.instructor.index')->with('success', 'Mentor Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        if ($instructor->photo) {
            Storage::delete('instructor/photo/' . $instructor->photo);
        }
        $instructor->user->delete();
        Instructor::destroy($instructor->id);

        return response()->json([
            'success' => true,
            'message' => 'Mentor berhasil dihapus.'
        ]);
    }
}
