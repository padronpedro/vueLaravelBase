<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Log;
use DB;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json(
            [
                'status' => 'success',
                'users' => $users->toArray()
            ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result=array(
            "status" => 'ERROR',
            "message" => '',
            "data" => array()
        );

        $auxUser = User::whereRaw("UPPER(TRIM(email)) = UPPER(TRIM('".$request->input('email')."'))")->get();
        if(count($auxUser)>0)
        {
            Log::info('New User added by '.Auth::user()->name.' :: '.json_encode($auxUser));

            $result['message'] = __('Email already exists');
        }else{
            $user = new User;
            $user->email = $request->input('email');
            $user->name = $request->input('name');
            $user->password = bcrypt($request->input('password'));
            $user->role_id = $request->input('role_id');
            $user->save();

            Log::info('New User added by '.Auth::user()->name.' :: '.$user);

            $result['status'] = 'SUCCESS';
            $result['message'] = __('Email already exists');
        }

        return response($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result=array(
            "status" => 'ERROR',
            "message" => '',
            "data" => array()
        );

        $user = User::find($id);

        if($user)
        {
            $user->permissions;
            $result['status'] = 'SUCCESS';
            $result['data'] = $user;
        }else{
            $result['status'] = 'ERROR';
            $result['message'] = __('User not found');
        }
        return response($result, 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result=array(
            "status" => 'ERROR',
            "message" => '',
            "data" => array()
        );

        $continueUpdate=true;
        $changeUser = User::find($id);
        if($changeUser->email != $request->input('email'))
        {
            $auxUser = User::whereRaw("UPPER(TRIM(email)) = UPPER(TRIM('".$request->input('email')."')) AND id<>".$id);
            if($auxUser)
            {
                $continueUpdate=false;
            }
        }
        //the new email doesnt exists --> modify the user information
        if($continueUpdate)
        {
            $changeUser->email = $request->input('email');
            $changeUser->name = $request->input('name');
            if($request->input('password')!='12345678')
            {
                $changeUser->password = bcrypt($request->input('password'));
            }
            if(strlen($request->input('role_id'))>0)
            {
                $changeUser->role_id = $request->input('role_id');
            }
            $changeUser->save();

            Log::info('Customer updated by '.Auth::user()->name.' :: '.$changeUser);


            //check if permissions changed
            $new_permissions = $request->input('permissions');
            $old_permissions_temp = $changeUser->permissions;
            $old_permissions=[];
            foreach($old_permissions_temp as $permission)
            {
                array_push($old_permissions,$permission->id);
            }
            Log::info('new permission :: '.json_encode($new_permissions));
            Log::info('old permission :: '.json_encode($old_permissions));

            //delete removed permissions
            $to_delete = array_diff($old_permissions, $new_permissions);
            foreach($to_delete as $oneDelete)
            {
                DB::table('permission_user')
                    ->where('user_id',$request->input('userId'))
                    ->where('permission_id',$oneDelete)
                    ->delete();
            }

            //add new permissions
            $to_add = array_diff($new_permissions, $old_permissions);
            foreach($to_add as $oneAdd)
            {
                DB::table('permission_user')->insert([
                    'user_id' => $request->input('userId'),
                    'permission_id' => $oneAdd
                ]);
            }

            $result['status'] = 'SUCCESS';
            $result['data']= $changeUser;
        }else{
            $result['status'] = 'ERROR';
            $result['message']= __('Email is already used');
        }

        return response($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //SuperAdmin user cannot be deleted
        if($id=='1')
        {
            return response([
                'status' => 'ERROR',
                'error' => 'admin.not.deleted',
                'message' => trans('You cannot delete the Administrator User')
            ], 400);
        }else{
            $deleteThisUser = User::find($id);

            if($deleteThisUser)
            {
                //remove permissions
                DB::table('permission_user')->where('user_id',$id)->delete();
                $deleteThisUser->delete();
                Log::info('Deleting User by '.Auth::user()->name.' :: User'.json_encode($deleteThisUser));

                return response([
                    'status' => 'SUCCESS',
                    'data' => $deleteThisUser
                ], 200);
            }else{
                return response([
                    'status' => 'ERROR',
                    'error' => 'user.notfound',
                    'message' => trans('User not found')
                ], 400);
            }
        }
    }

        /**
     * Get users for the data table.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getUsersForDataTable(Request $request)
    {
        $orderCol = $request->sortBy;
        if($orderCol=='name'){
            $orderCol = 'users.name';
        }
        $query = User::where('users.id','>','0')->orderBy($orderCol, $request->sortDesc);
        $query->leftJoin('roles','roles.id','=','users.role_id');
        $users = $query->paginate($request->itemsPerPage,['users.id as id','users.name as name','users.email as email','roles.name as role','users.is_active as is_active']);

        return $users;
    }

    /**
     * Change user status
     *
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function statusUser(Request $request)
    {
        if($request->input('id')==1)
        {
            return response([
                'status' => 'ERROR',
                'error' => 'admin.notchange',
                'message' => trans('You cannot change the status of the user SuperAdmin')
            ], 200);
        }else{
            $changeStatusUser = User::find($request->input('id'));

            if($changeStatusUser)
            {
                $changeStatusUser->is_active = ($changeStatusUser->is_active=='1') ? '0' : '1';
                $changeStatusUser->save();

                return response([
                    'status' => 'SUCCESS',
                    'data' => $changeStatusUser
                ], 200);
            }else{
                return response([
                    'status' => 'ERROR',
                    'error' => 'user.notfound',
                    'message' => trans('User not found')
                ], 200);
            }
        }
    }

    /**
     * Get list of roles from database
     *
     * @return json with roles
     */
    public function getRoles()
    {
        $result=array(
            "status" => 'ERROR',
            "message" => '',
            "data" => array()
        );

        $roles = DB::table('roles')->get();

        if($roles)
        {
            $result['status'] = 'SUCCESS';
            $result['data'] = $roles;
        }else{
            $result['message'] = __('Roles not found');
        }

        return response($result, 200);
    }

    /**
     * Get list of Permissions from database
     *
     * @return json with roles
     */
    public function getPermissions()
    {
        $result=array(
            "status" => 'ERROR',
            "message" => '',
            "data" => array()
        );

        $permissions = DB::table('permissions')->get();

        if($permissions)
        {
            $result['status'] = 'SUCCESS';
            $result['data'] = $permissions;
        }else{
            $result['message'] = __('Permissions not found');
        }

        return response($result, 200);
    }
}
