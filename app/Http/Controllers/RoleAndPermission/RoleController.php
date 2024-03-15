<?php

namespace App\Http\Controllers\RoleAndPermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\MenuItem;
use App\Models\MenuGroup;
use App\Models\Permission;
use App\Models\RoleHasPermission;
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role.index')->only('index');
        $this->middleware('permission:role.create')->only('create', 'store');
        $this->middleware('permission:role.edit')->only('edit', 'update');
        $this->middleware('permission:role.destroy')->only('destroy');
        $this->middleware('permission:role.show')->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = DB::table('roles')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->paginate(10);
        return view('permissions.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('permissions.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web',
        ]);
        return redirect()->route('role.index')->with('success', 'Role Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
        $permissions = $role->permissions->toArray();
        $menu = [];
        $menuItems = [];
        $arrayMenu = ['management','dashboard'];
        $menuNo = -1;
        foreach ($permissions as $permissionsKey => $permissionsValue) 
        {
           $expMenuName = explode('.', $permissionsValue['name']);
           $manage = end($expMenuName); 
           if(in_array($manage, $arrayMenu))
           {
                $menuNo++;
                $menu[$menuNo]['id'] = $permissionsValue['id'];
                $menu[$menuNo]['permission_name'] = $permissionsValue['name'];
           }else{
             array_push($menuItems, $permissionsValue['name']);
           }
        }
        foreach ($menu as $menuKey => $menuValue) 
        {
            $menuGroup = MenuGroup::where('permission_name',$menuValue['permission_name'])->first();
            if($menuGroup)
            {
                $menu[$menuKey]['name'] = $menuGroup->name;
            }
        }
        $ordinal = 0;
        foreach ($menu as $menuKey => $menuValue)
        {
            $menuItem = MenuItem::where('menu_group_id',$menuValue['id'])->get();
            $menu[$menuKey]['ordinal'] = $menuKey + 1;
            $menu[$menuKey]['items'] = [];
            if(!$menuItem->isEmpty())
            {
                foreach ($menuItem as $menuItemkey => $menuIitemvalue) 
                {
                    $permission = Permission::where('name',$menuIitemvalue['permission_name'])->first();
                    if($permission)
                    {
                        $RoleHasPermission = RoleHasPermission::where('permission_id',$menuValue['id'])
                        ->where('role_id',$role->id)->first();
                        if($RoleHasPermission)
                        {
                            if($RoleHasPermission->ordinal != NULL)
                            {
                                $ordinal = $RoleHasPermission->ordinal;
                            }else
                            {
                                $ordinal = $menuKey + 1;
                            }
                            $menu[$menuKey]['parent_id'] = $permission->id;
                            $menu[$menuKey]['ordinal'] = $ordinal;
                        }
                    }
                    $menu[$menuKey]['items'][$menuItemkey]['id'] = $menuIitemvalue['id'];
                    $menu[$menuKey]['items'][$menuItemkey]['name'] = $menuIitemvalue['name'];
                    $menu[$menuKey]['items'][$menuItemkey]['permission_name'] = $menuIitemvalue['permission_name'];
                }
            }
        }
        $menu = $this->array_sort_by_column($menu,'ordinal');
        return view('permissions.roles.ordering-menu', compact('menu','role'));
    }

    public function updateOrderingMenu(Request $request,$id)
    {
        $menuJson = json_decode($request->data);
        $lisMenuSorted = $menuJson[0];
        $no = 0;
        foreach ($lisMenuSorted as $key => $value) 
        {
            $no++;
            DB::table('role_has_permissions')
            ->where('permission_id',$value->id)->where('role_id',$id)->update(['ordinal'=>$no]);
        }
        return true;
    }

     public function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) 
     {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

       array_multisort($sort_col, $dir, $arr);
       return $arr;
      }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
        return view('permissions.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        //
        $role->update($request->validated());
        return redirect()->route('role.index')->with('success', 'Role Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role Deleted Successfully');
    }
}
