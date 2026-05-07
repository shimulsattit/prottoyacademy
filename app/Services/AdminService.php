<?php 

namespace App\Services;

use App\Models\Question;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\AdminRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminService {
    protected $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function login($data)
    {
        $guard = 'admin';

        $user = Admin::where('email', $data['email'])->where('status', 1)->first();

        if(!$user) {
            return 0;
        }
        
        if (Auth::guard($guard)->attempt($data)) {
            return $guard;
        }

        return 0;
    }

    public function createUser($request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required|min:2',
            'first_name' => 'required|min:4|max:20',
            'last_name' => 'required|min:4|max:25',
            'username' => [
                'required',
                'min:4',
                Rule::unique('admins')->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->whereNull('deleted_at'),
            ],
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $avatar = null;
        if($request->hasFile('avatar')) {
            $avatar = upload('system', $request->avatar);
        }

        $admin = $this->adminRepository->create($request->only(
            'surname',
            'first_name',
            'last_name',
            'username',
            'email',
            'new_password',
            'status'
        ), $avatar);

        $role = Role::find($request->roles);
        $admin->assignRole([$role->id]);

        return response()->json([
            'status' => true,
            'message' => 'Stuff Created Successfully',
            'goto' => route('portal.stuff.index')
        ]);
    }

    public function logout($guard)
    {
        Auth::guard($guard)->logout();
    }

    public function getAllUsers()
    {
        $data = $this->adminRepository->getAll();
        return DataTables::of($data)
            ->editColumn('name', function($model) {
                $image = $model->avatar ? asset($model->avatar) : asset('portal-resource/images/300-1.jpg');
                $fullName = $model->surname . ' '. $model->first_name . ' '. $model->last_name;
                $status = $model->status == 1 ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
                $html = '
                    <div class="row col">
                        <div class="col-auto">
                            <img src="'. $image .'" alt="'. $model->first_name .'" style="width: 50px" >
                        <div>
                        <div class="col">
                            Name: <b>'. $fullName .'</b> <br>
                            Username: <b>'. $model->username .'</b> <br>
                            Email: <b>'. $model->email .'</b> <br>
                            '. $status .'
                        <div>
                    </div>
                ';

                return $html;
            })
            ->editColumn('information', function($model) {
                
                $numberOfCategories = 0;
                $numberOfJobCategories = 0;
                $numberOfYears = 0;
                $numberOfExams = 0;
                $numberOfPassage = 0;
                $numberOfQuestions = 0;
                
                if($model->categories) {
                    $numberOfCategories = $model->categories->count();
                }
                
                if($model->job_categories) {
                    $numberOfJobCategories = $model->job_categories->count();
                }
                
                if($model->years) {
                    $numberOfYears = $model->years->count();
                }
                
                if($model->exams) {
                    $numberOfExams = $model->exams->count();
                }
                
                if($model->passages) {
                    $numberOfPassage = $model->passages->count();
                }
                
                
                $numberOfQuestions = Question::where('admin_id', $model->id)->count();
                
                $html = '
                    Number of Category: <b>'. $numberOfCategories .'</b> <br>
                    Number of Job Category: <b>'. $numberOfJobCategories .'</b> <br>
                    Number of Year: <b>'. $numberOfYears .'</b> <br>
                    Number of Exam: <b>'. $numberOfExams .'</b> <br>
                    Number of Passage: <b>'. $numberOfPassage .'</b> <br>
                    Number of Question: <b>'. $numberOfQuestions .'</b>
                ';

                return $html;
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->addColumn('action', function ($model) {
                return view('portal.stuff.action', compact('model'));
            })
            ->rawColumns(['action', 'information', 'name'])
            ->make(true);
    }

    public function getUserById($id)
    {
        return $this->adminRepository->getById($id);
    }

    public function updateUser($id, $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required|min:2',
            'first_name' => 'required|min:4|max:20',
            'last_name' => 'required|min:4|max:25',
            'username' => [
                'required',
                'min:4',
                Rule::unique('admins')->ignore($id)->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($id)->whereNull('deleted_at'),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $avatar = null;
        if($request->hasFile('avatar')) {
            $avatar = upload('system', $request->avatar);
        }

        $data = $request->only([
            'surname',
            'first_name',
            'last_name',
            'username',
            'email',
            'status'
        ]);
        
        if (isset($avatar)) {
            $data['avatar'] = $avatar;
        }
        
        $admin = $this->adminRepository->update($id, $data);

        if(isset($request->roles)) {
            if(count($admin->roles) > 0) {
                foreach($admin->roles->toArray() as $adminRole) {
                    $admin->removeRole($adminRole['name']);
                }
            }

            $role = Role::find($request->roles);
            if($role) {
                $admin->assignRole($role->name);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Stuff Update Successfully',
            'goto' => route('portal.stuff.index')
        ]);
    }

    public function deleteUser($id)
    {
        return $this->adminRepository->delete($id);
    }
}