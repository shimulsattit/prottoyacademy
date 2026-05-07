<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\JobCategory;
use App\Models\Year;
use App\Models\Exam;
use App\Models\Passage;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.settings.index');
    }

    public function websiteHeader()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.settings.website.header');
    }
    
    public function websiteFooter()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.settings.website.footer');
    }
    
    public function websiteHomePageSeo()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.settings.website.home-page-seo');
    }
    
    public function profile()
    {
        return view('portal.settings.profile');
    }
    
    public function password()
    {
        return view('portal.settings.password');
    }

    public function update(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $validator = Validator::make($request->all(), [
            'system_logo' => 'mimes:jpeg,bmp,png,jpg,svg,webp|max:2000',
            'system_favicon' => 'mimes:jpeg,bmp,png,ico,jpg,svg,webp|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $config_type = $request->config_type;
        $boolean_system_setting = config('system.boolean.' . $config_type);

        if ($boolean_system_setting) {
            foreach ($boolean_system_setting as $v) {
                $config = Setting::firstOrCreate(['name' => $v]);
                $config->value = 0;
                $config->save();
            }
        }

        foreach ($_POST as $key => $value) {
            if ($key == "_token") {
                continue;
            }

            $data = array();
            if ($key == 'header_menu_labels' || $key == 'header_menu_links' || $key == 'footer_menu_one_labels' || $key == 'footer_menu_one_links' || $key == 'footer_menu_two_labels' || $key == 'footer_menu_two_links' || $key == 'footer_menu_three_labels' || $key == 'footer_menu_three_links') {
                $data['value'] = json_encode($value);
            } else {
                $data['value'] = $value;
            }

            $data['updated_at'] = Carbon::now();
            if (Setting::where('name', $key)->exists()) {
                Setting::where('name', '=', $key)->update($data);
            } else {
                $data['name'] = $key;
                $data['created_at'] = Carbon::now();

                Setting::insert($data);
            }

            Session::put('settings.' . $key, $value);
        }

        if ($request->hasFile('system_logo')) {
            $fileName = upload('system', $request->system_logo);
            $logo['name'] = 'system_logo';
            $logo['value'] = $fileName;

            if (Setting::where('name', "system_logo")->exists()) {
                Setting::where('name', '=', "system_logo")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                Setting::insert($logo);
            }

            Session::put('settings.system_logo', $fileName);
        }

        if ($request->hasFile('system_favicon')) {
            $fileName = upload('system', $request->system_favicon);
            $logo['name'] = 'system_favicon';
            $logo['value'] = $fileName;

            if (Setting::where('name', "system_favicon")->exists()) {
                Setting::where('name', '=', "system_favicon")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                Setting::insert($logo);
            }

            Session::put('settings.system_favicon', $fileName);
        }
        
        if ($request->hasFile('card_one_picture')) {
            $fileName = upload('system', $request->card_one_picture);
            $logo['name'] = 'card_one_picture';
            $logo['value'] = $fileName;

            if (Setting::where('name', "card_one_picture")->exists()) {
                Setting::where('name', '=', "card_one_picture")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                Setting::insert($logo);
            }

            Session::put('settings.card_one_picture', $fileName);
        }

        if ($request->hasFile('card_two_picture')) {
            $fileName = upload('system', $request->card_two_picture);
            $logo['name'] = 'card_two_picture';
            $logo['value'] = $fileName;

            if (Setting::where('name', "card_two_picture")->exists()) {
                Setting::where('name', '=', "card_two_picture")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                Setting::insert($logo);
            }

            Session::put('settings.card_two_picture', $fileName);
        }
        
        if ($request->hasFile('card_three_picture')) {
            $fileName = upload('system', $request->card_three_picture);
            $logo['name'] = 'card_three_picture';
            $logo['value'] = $fileName;

            if (Setting::where('name', "card_three_picture")->exists()) {
                Setting::where('name', '=', "card_three_picture")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                Setting::insert($logo);
            }

            Session::put('settings.card_three_picture', $fileName);
        }
        
        if ($request->hasFile('card_four_picture')) {
            $fileName = upload('system', $request->card_four_picture);
            $logo['name'] = 'card_four_picture';
            $logo['value'] = $fileName;

            if (Setting::where('name', "card_four_picture")->exists()) {
                Setting::where('name', '=', "card_four_picture")->update($logo);
            } else {
                $logo['created_at'] = Carbon::now();
                Setting::insert($logo);
            }

            Session::put('settings.card_four_picture', $fileName);
        }

        return response()->json([
            'status' => true,
            'message' => 'Configuration updated',
            'load' => true
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname'       => 'required|string|min:2|max:10',
            'first_name'    => 'required|string|min:4|max:25',
            'last_name'     => 'required|string|min:2|max:25',
            'username'      => 'required|string|min:4|max:25|unique:admins,username,'. auth()->guard('admin')->user()->id,
            'email'         => 'required|email|unique:admins,email,'. auth()->guard('admin')->user()->id,
            'avatar'        => 'mimes:jpeg,png,jpg,svg,webp|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $admin = Admin::find(auth()->guard('admin')->user()->id);
        if(!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin Not Found.'
            ]);
        }

        $admin->surname     = $request->surname;
        $admin->first_name     = $request->first_name;
        $admin->last_name     = $request->last_name;
        $admin->username     = $request->username;
        $admin->email     = $request->email;
        if($request->hasFile('avatar')) {
            $admin->avatar = upload('system', $request->avatar);
        }
        $admin->save();

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Profile Updated Successfully.'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $admin = Admin::find(auth()->guard('admin')->user()->id);

        // Check if current password is correct
        if (!Hash::check($request->old_password, $admin->password)) {
            return response()->json(['status' => false, 'message' => 'Current password is incorrect']);
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['status' => true, 'message' => 'Password updated successfully']);
    }

    public function slugCheck(Request $request)
    {
        $slug = $request->get('slug');
        $id = $request->get('id');

        if (isset($id)) {
            $exists = Category::where('slug', $slug)->where('id', '!=', $id)
                ->union(JobCategory::where('slug', $slug))
                ->union(Year::where('slug', $slug))
                ->union(Exam::where('slug', $slug))
                ->exists();
        } else {
            $exists = Category::where('slug', $slug)
                ->union(JobCategory::where('slug', $slug))
                ->union(Year::where('slug', $slug))
                ->union(Exam::where('slug', $slug))
                ->exists();
        }

        return response()->json(['exists' => $exists]);
    }

    public function searchForCategory(Request $request)
    {
        if(!isset($request->searchTerm)){
            $categories = Category::where('parent_id', null)->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });
        } else {
            $search = $request->searchTerm;

            $categories = Category::where('name','like', "%$search%")->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });

        }

        return response()->json($json);
    }

    public function searchForAllCategory(Request $request)
    {
        if(!isset($request->searchTerm)){
            $categories = Category::all();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });
        } else {
            $search = $request->searchTerm;

            $categories = Category::where('name','like', "%$search%")->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });

        }

        return response()->json($json);
    }

    public function searchByJobCategory(Request $request)
    {
        $categoryIds = explode(',', $request->category_id);
        if(empty($categoryIds) || $categoryIds[0] == '') {
            if(!isset($request->searchTerm)){
                $categories = JobCategory::orderBy('name', 'ASC')->where('status', 1)->orderBy('id', 'DESC')->get();

                $json = $categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'text' => $category->name,
                    ];
                });
            } else {
                $search = $request->searchTerm;

                $categories = JobCategory::where('name','like', "%$search%")->where('status', 1)->orderBy('id', 'DESC')->get();

                $json = $categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'text' => $category->name,
                    ];
                });

            }
        } else {
            if(!isset($request->searchTerm)){
                $categories = JobCategory::orderBy('name', 'ASC')->whereIn('category_id', $categoryIds)->where('status', 1)->orderBy('id', 'DESC')->get();

                $json = $categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'text' => $category->name,
                    ];
                });
            } else {
                $search = $request->searchTerm;

                $categories = JobCategory::whereIn('category_id', $categoryIds)->where('name','like', "%$search%")->where('status', 1)->orderBy('id', 'DESC')->get();

                $json = $categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'text' => $category->name,
                    ];
                });

            }
        }

        return response()->json($json);
    }

    public function searchForPassage(Request $request)
    {
        if(!isset($request->searchTerm)){
            $categories = Passage::orderBy('name', 'ASC')->where('status', 1)->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                ];
            });
        } else {
            $search = $request->searchTerm;

            $categories = Passage::where('name','like', "%$search%")->where('status', 1)->orderBy('name', 'ASC')->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                ];
            });

        }

        return response()->json($json);
    }

    public function searchByYear(Request $request)
    {
        if(!isset($request->searchTerm)){
            $years = Year::where('status', 1)->orderBy('name', 'ASC')->get();
            $json = $years->map(function($year) {
                return [
                    'id' => $year->id,
                    'text' => $year->name,
                ];
            });
        } else {
            $search = $request->searchTerm;

            $years = Year::where('name','like', "%$search%")->where('status', 1)->orderBy('name', 'ASC')->get();
            $json = $years->map(function($year) {
                return [
                    'id' => $year->id,
                    'text' => $year->name,
                ];
            });
        }

        return response()->json($json);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
        
            // Define dynamic folders
            $type = 'uploads'; // You can also make this dynamic if needed
            $folder = 'ckeditor';
        
            // Generate filename
            $fileName = time() . '.' . $file->getClientOriginalExtension();
        
            // Store file in: storage/app/public/uploads/ckeditor/
            $file->storeAs($type . '/' . $folder, $fileName, 'public');
        
            // Generate public URL
            $url = asset("storage/{$type}/{$folder}/{$fileName}");
        
            // CKEditor integration
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg');</script>";
        
            return response($response)->header('Content-Type', 'text/html; charset=utf-8');
        }
    }
}
