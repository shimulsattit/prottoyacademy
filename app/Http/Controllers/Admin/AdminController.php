<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Exam;
use App\Models\JobCategory;
use App\Models\Passage;
use App\Models\Question;
use App\Models\QuestionDescriptionLog;
use App\Models\Year;
use App\Services\AdminService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{

    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function dashboard()
    {
        // Number of Admin
        $numberOfAdmins = Admin::count();

        // Number of Categories
        $numberOfCategories = Category::count();

        // Number of Exams
        $numberOfExams = Exam::count();

        // Number of Job Category
        $numberOfJobCategories = JobCategory::count();

        // Number of Year
        $numberOfYears = Year::count();

        // Number of Passage
        $numberOfPassage = Passage::count();

        // Number of Active Questions
        $numberOfActiveQuestions = Question::where('status', 1)->count();

        // Number of Inactive Questions
        $numberOfInactiveQuestions = Question::where('status', 0)->count();

        return view('portal.dashboard', compact('numberOfAdmins', 'numberOfCategories', 'numberOfExams', 'numberOfJobCategories', 'numberOfYears', 'numberOfPassage', 'numberOfActiveQuestions', 'numberOfInactiveQuestions'));
    }

    public function getActivityData(Request $request)
    {
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));

        $adminId = auth()->guard('admin')->id(); // Make sure admin guard is used

        $data = DB::table('questions')
            ->select(DB::raw('DATE(created_at) as raw_date'), DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('admin_id', $adminId)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('raw_date', 'asc')
            ->get()
            ->map(function ($item) {
                $item->date = \Carbon\Carbon::parse($item->raw_date)->format('d F, Y'); // 02 June, 2025
                unset($item->raw_date);
                return $item;
            });

        return response()->json($data);
    }

    public function getActivityDetails(Request $request)
    {
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));

        $adminId = auth()->guard('admin')->id();

        $data = DB::table('question_description_logs')
            ->select(DB::raw('DATE(created_at) as raw_date'), DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('admin_id', $adminId)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('raw_date', 'asc')
            ->get()
            ->map(function ($item) {
                $item->date = Carbon::parse($item->raw_date)->format('d F, Y'); // e.g., 02 June, 2025
                unset($item->raw_date);
                return $item;
            });

        return response()->json($data);
    }



    public function fetchDescriptionLogs(Request $request)
    {
        $type = $request->type ?? 'daily';

        $query = QuestionDescriptionLog::select([
            'admin_id',
            DB::raw('COUNT(*) as total'),
            DB::raw("DATE(created_at) as group_date")
        ])
        ->groupBy('admin_id', 'group_date');

        if ($type == 'weekly') {
            $query = QuestionDescriptionLog::select([
                'admin_id',
                DB::raw('COUNT(*) as total'),
                DB::raw("YEARWEEK(created_at, 1) as group_date")
            ])->groupBy('admin_id', 'group_date');
        } elseif ($type == 'monthly') {
            $query = QuestionDescriptionLog::select([
                'admin_id',
                DB::raw('COUNT(*) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as group_date")
            ])->groupBy('admin_id', 'group_date');
        }

        $logs = $query->with('user')->get();

        // Organize data for chart
        $grouped = [];
        foreach ($logs as $log) {
            $name = $log->user->first_name . ' '. $log->user->last_name;
            $grouped[$name][$log->group_date] = $log->total;
        }

        return response()->json($grouped);
    }


    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->adminService->getAllUsers();
        }

        return view('portal.stuff.index');
    }

    public function create()
    {
        $roles = Role::all();

        return view('portal.stuff.create', compact('roles'));
    }

    public function show($id)
    {
        $user = $this->adminService->getUserById($id);
        if (!$user) {
            return redirect()->route('portal.stuff.index')->with('error', 'User not found');
        }

        $stuffQuestions = DB::table('questions')
            ->select(DB::raw('DATE(created_at) as date'), 'admin_id', DB::raw('COUNT(*) as total_questions'))
            ->where('admin_id', $user->id)
            ->whereNull('deleted_at')
            ->groupBy('admin_id', DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('portal.stuff.show', compact('user', 'stuffQuestions'));
    }

    public function store(Request $request)
    {
        return $this->adminService->createUser($request);
    }

    public function edit($id)
    {
        $model = $this->adminService->getUserById($id);
        $roles = Role::all();
        return view('portal.stuff.edit', compact('model', 'roles'));
    }

    public function update(Request $request, $id)
    {
        return $this->adminService->updateUser($id, $request);
    }

    public function destroy($id)
    {
        $deleted = $this->adminService->deleteUser($id);
        if ($deleted) {
            return response()->json(['status' => true, 'load' => true, 'message' => 'User deleted successfully']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function logout()
    {
        // Helpers::logout('admin');
        $this->adminService->logout('admin');  
        
        return response()->json([
            'status' => true, 
            'goto' => route('portal.login'),
            'message' => "Logout successful"
        ]);
    }
}