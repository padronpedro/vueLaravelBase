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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        //
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
}
