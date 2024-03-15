<?php

namespace App\View\Components;

use App\Models\MenuGroup;
use App\Models\RoleHasPermission;
use App\Models\Permission;
use Illuminate\View\Component;
use Auth;
class Sidebar extends Component
{
    public $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title)
    {
        //
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $rolesAuth = Auth::user()->roles->toArray();
        $arrRoles = [];
        foreach ($rolesAuth as $key => $value) 
        {
           array_push($arrRoles, $value['id']);
        }
        $menuGroups = MenuGroup::with('menuItems')->get();
        $menuGroups = json_decode(json_encode($menuGroups),true);
        $arrPermissionId = [];
        foreach ($menuGroups as $key => $item) 
        {
            $permission = Permission::where('name',$item['permission_name'])->first();
            if($permission)
            {
                array_push($arrPermissionId, $permission->id);
                $menuGroups[$key]['parent_id'] = $permission->id;
            }
        }
        $orderingPermission = RoleHasPermission::whereIn('role_id',$arrRoles)->whereIn('permission_id',$arrPermissionId)->get();
        $orderingPermission = json_decode(json_encode($orderingPermission),true);
        $filter = [];
        foreach ($orderingPermission as $value)
        {
            $filter[$value['permission_id']] = $value;
        }
        $orderingPermission = array_values($filter);
        foreach ($orderingPermission as $key => $value) 
        {
            $check = RoleHasPermission::whereIn('role_id',$arrRoles)
                    ->where('permission_id',$value['permission_id'])
                    ->whereNotNull('ordinal')
                    ->first();
            if($check)
            {
                $orderingPermission[$key]['ordinal'] = $check->ordinal;
            }
        }
        $jml = count($orderingPermission);
        foreach ($orderingPermission as $key => $value)
        {
            if($value['ordinal'] == NULL)
            {
                $orderingPermission[$key]['ordinal'] = $jml;
                $jml++;
            }
        }
        $finishOrdering = [];
        foreach ($orderingPermission as $key => $value) 
        {
            $finishOrdering[$value['permission_id']] = $value['ordinal'];
        }
        foreach ($menuGroups as $key => $item)
        {
            $menuGroups[$key]['ordinal'] = $key+1;
            if(isset($item['parent_id']))
            {
                if(isset($finishOrdering[$item['parent_id']]))
                {
                    $menuGroups[$key]['ordinal'] = $finishOrdering[$item['parent_id']];
                }
            }
        }
        $menuGroups = $this->array_sort_by_column($menuGroups,'ordinal');
        //dd($menuGroups);
        return view('components.sidebar', compact('menuGroups'));
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
}
