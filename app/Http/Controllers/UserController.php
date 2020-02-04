<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Session;
use Auth;
use Newsletter;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function __construct()
    {
    	// constructor code  here ...
    }

    /*=========================================
    =        Methods for Role {admin}     =
    =========================================*/


    /**
     *
     * Shows list of all users (Admin Panel)
     *
     */
    public function index()
    {
    	$users = User::all();
    	return view('dashboard.users.index')->withUsers($users);
    }

    /**
     *
     * Shows create user form (Admin Panel)
     *
     */
    public function create()
    {
    	$roles = Role::all();
    	return view('dashboard.users.create')->withRoles($roles);
    }

    /**
     *
     * Stores a new user in database
     *
     */
    public function store(Request $request)
    {
    	$this->validate($request,[
                'first_name'          	=> ['required', 'string', 'max:255'],
                'last_name'         	=> ['required', 'string', 'max:255'],
                'email'      			=> ['required', 'string', 'email', 'unique:users'],
                'username'      		=> ['required', 'string', 'max:50', 'unique:users'],
                'password'     			=> ['required', 'string', 'min:6', 'confirmed']
            ]);

            $user = User::create([
                'first_name'          	=>  $request->first_name,
                'last_name'         	  =>  $request->last_name,
                'email'      			      =>  $request->email,
                'username'      		    =>  $request->username,
                'password'      		    =>  Hash::make($request->password)
            ]);
            if($user)
            {
                if($user->assignRole($request->user_role))
                {
                    Newsletter::subscribe($request->email,[],'registeredUsersList');
                    $this->success('User Created Successfully!');
                }
                else
                {
                   $this->error();
                }
            }
            else
            {
                $this->error();
            }
        return redirect()->route('dashboard.users.index');
    }

    /**
     *
     * Shows edit user form (Admin Panel)
     *
     */

    public function edit(User $user)
    {
    	return view('dashboard.users.edit')->withUser($user);
    }

    /**
     *
     * Updates an existing user
     *
     */
    public function update(Request $request, User $user)
    {
 		// update code here
    }

    /**
     *
     * Blocks an existing user (Admin Panel)
     *
     */
    public function block(User $user)
    {
    	$user->status = 0;
    	if($user->save())
    	{
    		$this->success('User Blocked Successfully!!');
    	}
    	else
    	{
    		$this->error();
    	}
    	return redirect()->back();
    }

    /**
     *
     * Activates a blocked user
     *
     */
    public function activate(User $user)
    {
    	$user->status = 1;
    	if($user->save())
    	{
    		$this->success('User Activated Successfully!!');
    	}
    	else
    	{
    		$this->error();
    	}
    	return redirect()->back();
    }


    /**
     *
     * Delete specific user
     *
     */
    public function destroy(User $user)
    {
    	if($user->delete())
    	{
    		$this->success("User destroyed Successfully!!");
    	}
    	else
    	{
    		$this->error();
    	}
    	return redirect()->back();
    }

    /**
     *
     * Verify an account
     *
     */
    public function ajaxUpdateUserCol(Request $request)
    {
      $user = User::find($request->user_id);
      if($user)
      {
        $col = $request->col;
        $user->$col = $request->status;
        $user->save();

        if($col == 'mail_subscription')
        {
          if($request->status == 1)
          {
            Newsletter::subscribe($user->email,[],'registeredUsersList');
          }
          else
          {
            Newsletter::unsubscribe($user->email,'registeredUsersList');
          }
        }
        return response()->json(['success'=>'success'],200);
      }
      else
      {
        return response()->json(['error'=>'error'],200);
      }

    }


    /**
     *
     * Update User Role
     * Ajax Handler
     */
    public function ajaxUpdateUserRole(Request $request)
    {
      $user = User::find($request->user_id);
      if($user)
      {
        if($request->role == 'admin')
        {
          if($request->status == 1)
          {
            $user->syncRoles(['admin']);
          }
          else
          {
            $user->syncRoles(['user']);
          }
        }
        elseif ($request->role == 'moderator')
        {
          if($request->status == 1)
          {
            $user->syncRoles(['moderator']);
          }
          else
          {
            $user->syncRoles(['user']);
          }
        }

        return response()->json(['success'=>'success'],200);
      }
      else
      {
        return response()->json(['error'=>'error'],200);
      }
    }



     /*=====  End of Methods for Role {admin}  ======*/


    /*=========================================
    =            Methods for Role {user}     =
    =========================================*/

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.users.profile.index')->withUser($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $private_fields = '';

        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->position     = $request->position;
        $user->working_in   = $request->working_in;
        $user->subjects     = $request->subjects;
        $user->country      = $request->country;
        $user->about_me     = $request->about_me;

        if(!blank($request->private))
        {
            $private_fields = implode(',', $request->private);
        }

        $user->private_info = $private_fields;

        if($user->save())
        {
            $this->success("Profile Updated Successfully!!");
        }
        else
        {
            $this->error();
        }
        return redirect()->route('dashboard.user.profile');
        }

        /**
         *
         * Updates profile picture
         *
         */
        public function updateProfilePicture(Request $request)
        {
            $user = Auth::user();
            if(!$user->hasRole('user'))
                abort(403);
            if($request->hasFile('profile_picture_uploader'))
            {
                  $attachment = $request->file('profile_picture_uploader');
                  $attachmentName = time().$attachment->getClientOriginalName();
                  $attachment->move(public_path('irh_assets/uploads/profile_pictures/'),$attachmentName);
                  $profile_picture = $attachmentName;
                  $user->profile_picture = $profile_picture;
                  if($user->save())
                  {
                    $this->success('Profile Picture Updated Successfully!');
                    return redirect()->back();
                  }
            }
            else
            {
                $this->error("No File Found");
            }
            return redirect()->back();
        }


    /*=====  End of Methods for Role {user}  ======*/


    public function userActivate(Request $request, $user_email){
        try {
          $email = decrypt($user_email);
          $user = User::where('email', $email)->first();
          if(!$user){
            return redirect()->route('theme.index');
          }
          else{
            if ($user->validate == '1') {
             return redirect()->route('login');
            }
            $user->validate = '1';
            $user->save();
            Session::flash('reg_success','E-mail verification completed successfully!');
            return redirect()->route('login');
          }
        } catch (\Exception $e) {
          return redirect()->route('theme.index');
        }
    }


}
