<?php
namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\PermissionsRequest;
use App\Repositories\PermissionsRepository;

class RoleController extends Controller
{
    protected $roleRepository;
    protected $permissionsRepository;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(RoleRepository $roleRepository, PermissionsRepository $permissionsRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionsRepository = $permissionsRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $roles = $this->roleRepository->getRoles();

            return Datatables::of($roles)
            ->addIndexColumn()
            ->addColumn('name', function ($role){
                return ucfirst($role->name);
            })
            ->addColumn('actions', function ($role) {
                $editButton = '<a href="'.route('roles.edit', $role->id).'" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>';
    
                $permissionButton = '<a href="'.route('roles.edit.permissions', $role->id).'" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Permission">
                                        <i class="fa-solid fa-person-circle-xmark"></i>
                                    </a>';
    
                $deleteButton = '';
    
                // Check if the role is neither 'admin' nor 'estimator'
                if (strpos($role->name, 'admin') === false && strpos($role->name, 'estimator') === false) {
                    $deleteButton = '<button type="button" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Delete" data-id="'.$role->id.'" id="deleteRole">
                                        <i class="fa fa-times"></i>
                                    </button>';
                }
    
                $editButton = Gate::check('role.edit') ? $editButton : '';
                $permissionButton = Gate::check('role.edit') ? $permissionButton : '';
                $deleteButton = Gate::check('role.delete') ? $deleteButton : '';
    
                return '<div class="btn-group">' . $editButton . $permissionButton . $deleteButton . '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
        }
        return view('roles.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissionsByModule = $this->roleRepository->permissionsByModule();
        return view('roles.create',compact('permissionsByModule'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = $this->roleRepository->store($request);
        if($role)
        {
            return redirect()->route('roles.index')->with('success','Role created successfully');
        } else {
            return redirect()->route('roles.index')->with('error','Role not created! Something went wrong.');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->roleRepository->getRole($id);
        return view('roles.edit',compact('role'));
    }

    public function editPermissions($id)
    {
        $role = $this->roleRepository->getRole($id);
        $permissionsByModule = $this->roleRepository->permissionsByModule();
        return view('roles.permissions',compact('role', 'permissionsByModule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleRepository->update($request, $id);
        if($role)
        {
            return redirect()->back()->with('success','Role updated successfully');
        } else {
            return redirect()->back()->with('success','Role not updated! Something went wrong');
        }
    }

    public function updatePermissions(PermissionsRequest $request, $id)
    {
        $role = $this->permissionsRepository->updatePermissions($request, $id);
        if($role)
        {
            return redirect()->back()->with('success','Permissions updated successfully');
        } else {
            return redirect()->back()->with('success','Permissions not updated! Something went wrong');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->roleRepository->delete($id);
        return response()->json([
            'message' => 'Role deleted successfully',
        ]);
    }
}
