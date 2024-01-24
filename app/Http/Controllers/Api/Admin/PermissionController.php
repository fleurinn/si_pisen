<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        //get permissions
        $permissions = Permission::when(request()->search, function($permissions) {
            $permissions = $permissions->where('name', 'like', '%', request()->search, '%');
        })->latest()->paginate(5);

        //appened query string to pagination links 
        $permissions->appends(['search' => request()->search]);

        //return with api resource
        return new PermissionResource(true, 'List Data Permissions', $permissions);
    }

    /**
     * all
     * 
     * @return void
     */
    public function all()
    {
        //get permissions
        $permissions = Permission::latest()->get();
        
        //return with api resource
        return new PermissionResource(true, 'List Data Permissions', $permissions);
    }
}
