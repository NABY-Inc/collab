<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class systemUsersController extends Controller
{
    /* Display a listing of the users. */
    public function index()
    {
        $users = $this->allUsers();
        return view('admin.users.index',compact('users'));
    }

    /* Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $data = $this->validateData(); // Calling validate data
        if ($request->hasFile('profile')) { // Checking if request has image
            $request->validate(['profile'  => 'image|mimes:png,jpg,jpeg,JPEG,JPG,PNG|max:2048']);
            $fileName = time().'.'.$request->file('profile')->getClientOriginalExtension();
            $request->file('profile')->move(public_path('uploads/users'), $fileName);
            $data = $this->array_push_assoc($data, 'profile', $fileName);
        }
        $data = $this->array_push_assoc($data, 'password', bcrypt('pcsuser')); // Adding password to the data
        User::create($data); // Creating the user
        return $this->allUsers();
    }

    /* Display the specified resource. */
    public function show($id)
    {
        //
    }
    
    /* Update the specified resource in storage. */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $data = $this->validateData(); // Calling validate data
        if ($request->hasFile('profile')) { // Checking if request has image
            $request->validate(['profile'  => 'image|mimes:png,jpg,jpeg,JPEG,JPG,PNG|max:2048']);
            $fileName = time().'.'.$request->file('profile')->getClientOriginalExtension();
            $request->file('profile')->move(public_path('uploads/users'), $fileName);
            $data = $this->array_push_assoc($data, 'profile', $fileName);
        }
        if ($request->resetPass == 1) {
            // Setting default password & appending to data
            $data = $this->array_push_assoc($data, 'password', bcrypt('pcsuser')); 
        }
        User::find($id)->update($data); // Updating the user
        return $this->allUsers();
    }

    /* Remove the specified resource from storage. */
    public function destroy($id)
    {
        //
    }

    // Validating input fields
    public function validateData()
    {
        $data = request()->validate([
            'name'     => 'required',
            'contact'  => 'required',
            'email'    => ['required', 'email'],
            'role'     => 'required',
        ]);
        return $data;
    }

    // Custom function to add default password
    public function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }

    // Updating user activeness
    public function toggleActive(Request $request,$id)
    {
        $userData = User::find($id)->update(['active' => !$request->active]); // Finding and updating the user status
        return $this->allUsers();
    }

    // All users
    public function allUsers()
    {
        $data = User::orderBy('role','desc')->get();
        return $data;
    }
}
