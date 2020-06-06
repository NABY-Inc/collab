<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class systemUsersController extends Controller
{
    private $error = 0;
    private $success = 0;
    
    /* Display a listing of the resource. */
    public function index()
    {
        $systemUsers = 'App\User'::orderByDesc('active')->paginate(8);
        $error = $this->error;
        $success = $this->success;
        return view('admin.users.index', compact('systemUsers','error','success'));
    }

    /* Store a newly created resource in storage. */
    public function store(Request $request)
    {
        try {
            $data = $this->validateData(); // Calling validate data
            $data = $this->array_push_assoc($data, 'password', bcrypt('pcsuser')); // Adding password to the data
            'App\User'::create($data); // Creating the user
            $this->success = 1;
            return $this->index();
        } catch (\Exception $e) {
            $this->error = 1;
            return $this->index();
        }
    }

    /* Send the user detail for update. */
    public function edit($id)
    {
        return 'App\User'::find($id);
    }

    /* Update the specified resource in storage.*/
    public function updateNew(Request $request)
    {
        try {
            $data = $this->validateData(); // Calling validate data
            if ($request->resetPass == 1) { // Checking if we want to reset password
                $data = $this->array_push_assoc($data, 'password', bcrypt('pcsuser')); // Adding password to the data
            }
            'App\User'::find($request->user_id)->update($data); // Creating the user
            $this->success = 2;
            return $this->index();
        } catch (\Exception $e) {
            return $this->index();
        }
    }

    // Validating input fields
    public function validateData()
    {
        $data = request()->validate([
            'name'     => 'required',
            'contact'  => 'required',
            'email'    => 'required',
            'role'     => 'required'
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
    public function toggleActive($id)
    {
        $userData = 'App\User'::find($id); // Finding the user
        $userData = $userData->active; // Taking the user active
        if ($userData == 1) { // If active, we deactive
            $userData = 'App\User'::find($id)->update(['active' => 0]);
            return 0;
        }else{ // else we active
            $userData = 'App\User'::find($id)->update(['active' => 1]);
            return 1;
        }
    }
}
