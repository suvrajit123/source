<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\ResourceBanEvent;
use App\Events\AdminUserContactEvent;
use App\Resource;
use App\Donation;
use App\Option;
use App\ResourceDownload;
use App\ResourceCategory;
use App\UserRourceVisit;
use App\ResourceFlag;
use App\ResourceLike;
use App\ResourceTag;
use App\ResourceReview;
use App\Subscriber;
use App\Setting;
use App\Tag;
use App\User;
use App\Notice;
use App\MailSetting;
use App\RoleUser;
use App\Testimonial;
use App\Http\Requests\CreateUserRequest;
use App\Page;
use App\FAQMain;
use App\FAQSub;
use DB;
use Illuminate\Support\Carbon;
use App\Visitor;
use App\UserNotification;
use Auth;
use Session;
use Hash;

class AdminController extends Controller
{


    public function index(Request $request){
     
    }



    public function viewSavedResource(Request $request)
    {
        $allParam = $request->all();
        $resources = null;

        $saved_resource_text = Auth::user()->saved_resources;
        if (blank($saved_resource_text)) {
             $filters = Tag::all();
            return view('dashboard.admin.static.saved_resource',[
                                    'resources' => [],
                                    'allParam' => [],
                                    'filters' => $filters]);
        } 
        else{
            $saved_resource_array = explode(",", $saved_resource_text);

            if (isset($allParam['search']) && !blank($allParam['search'])) {
                $resources = DB::table('resources')
                        ->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews')
                        ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                        ->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')
                        ->whereIn('resources.id', $saved_resource_array)
                        ->where(function($q) use($allParam){$q->where('resources.title', 'like', ("%" . $allParam['search'] . "%"))->orWhere('resources.description', 'like', ("%" . $allParam['search'] . "%"));})
                        ->paginate(10);

            }
            elseif(isset($allParam['sort']) && !blank($allParam['sort'])){

                $searchArray = [];

                if (isset($allParam['res']) || isset($allParam['age'])) {
                    if (isset($allParam['res']) && !blank($allParam['res'])) {
                        array_push($searchArray, $allParam['res']);
                    }

                    if (isset($allParam['age']) && !blank($allParam['age'])) {
                        array_push($searchArray, $allParam['age']);
                    }
                }
                //$searchArray = implode(",", $searchArray);

                $sortBy = "";
                if ($allParam['sort'] == "relevance") {
                    $sortBy = ['id', 'ASC'];
                }
                elseif($allParam['sort'] == "newest") {
                    $sortBy = ['created_at', 'ASC'];
                }
                elseif($allParam['sort'] == "mdl") {
                    $sortBy = ["downloads", "DESC"];
                }
                elseif($allParam['sort'] == "hr") {
                    $sortBy = ["rate", "DESC"];
                }

                $resources = DB::table('resources')->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews', 'res_view_count.stars AS rate')
                    ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                    ->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')
                    ->whereIn('resources.id', $saved_resource_array)
                    ->when(!blank($searchArray), function ($q) use($searchArray){
                        return $q->whereIn('resources.id', DB::table('resource_tag')->select("resource_id")->whereIn('tag_id', $searchArray)->groupBy('resource_id')->get()->pluck('resource_id')->toArray());
                    })->orderBy($sortBy[0], $sortBy[1])->paginate(10);

            }
            else{
                $resources = DB::table('resources')->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews')->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')->whereIn('resources.id', $saved_resource_array)->paginate(10);
            }
            $filters = Tag::all();
            return view('dashboard.admin.static.saved_resource',[
                                    'resources' => $resources,
                                    'allParam' => $allParam,
                                    'filters' => $filters]);
        }
    }



    public function createResources(Request $request)
    {
        $filters = Tag::all();
        $resourceCategory = ResourceCategory::all();
        return view('dashboard.admin.resources.edit_resource', [
                                'edit' => '0',
                                'filters' => $filters,
                                'resourceCategory' => $resourceCategory
                            ]);
    }



    public function saveResources(Request $request)
    {
        $allInputs = $request->all();
        if ($request->has('resid')) {
            if ($request->has('form_name')) {
                if ($allInputs['form_name'] == 'link0') {
                    $resource = Resource::where('id', $request->resid)->first();
                    $resource->title = $request->resource_title;
                    $resource->description = $request->description;
                    $resource->embed_link = $request->embed_url;
                    $resource->resource_status = 'draft';


                    if (isset($allInputs['resource_attachment'])) {
                        $file = $request->file('resource_attachment');
                        $fileName = $resource->user_id . '.' . $file->getClientOriginalExtension();
                        $destinationPath = 'irh_assets/uploads/resource_files';
                        $file->move($destinationPath,$fileName);
                        $resource->resource_attachment = $fileName;
                    }

                    if(isset($allInputs['preview_attachment'])){
                        $file = $request->file('preview_attachment');
                        $fileName = $resource->user_id . '.' . $file->getClientOriginalExtension();
                        $destinationPath = 'irh_assets/uploads/resource_preview';
                        $file->move($destinationPath,$fileName);
                        $resource->preview_attachment = $fileName;
                    }
                    elseif(isset($allInputs['preview_attachment_hidden']) && !blank($allInputs['preview_attachment_hidden'])){

                    }
                    elseif(isset($allInputs['generate_preview_attachment'])){
                        $resource->preview_attachment = 'dummypreview.png';
                    }
                    $resource->save();

                    $_resource = Resource::where('id', $request->resid)->first();
                    if (!blank($_resource->title) && !blank($_resource->description) && !blank($_resource->resource_attachment) && !blank($_resource->preview_attachment)) {
                        return response()->json(['success' => '1', 'addclass' => 'whitBack'], 200);
                    }
                    else{
                        return response()->json([
                            'success' => '1', 
                            ], 201);
                    }
                }
                elseif($allInputs['form_name'] == 'link1'){
                    $resource = Resource::where('id', $request->resid)->first();
                    $resource->category_id = $request->category_id;
                    $resource->save();

                    if(isset($allInputs['age_group']) && $allInputs['age_group']  && count($allInputs['age_group'])){
                        $tags = Tag::where('tag_group', 'Age Group')->get()->pluck('id')->toArray();
                        if (count($tags)) {
                            $tags = implode(",", $tags);
                            DB::delete('DELETE FROM resource_tag WHERE resource_id = ' . $request->resid . ' AND tag_id IN (' . $tags . ')');
                        }
                        foreach ($allInputs['age_group'] as $key => $value) {
                            ResourceTag::create([
                                                    'resource_id' => $request->resid,
                                                    'tag_id' => $value
                                                ]);
                        }
                    }

                    if(isset($allInputs['resource_type']) && $allInputs['resource_type']  && count($allInputs['resource_type'])){
                        $tags = Tag::where('tag_group', 'Resource Type')->get()->pluck('id')->toArray();
                        if (count($tags)) {
                            $tags = implode(",", $tags);
                            DB::delete('DELETE FROM resource_tag WHERE resource_id = ' . $request->resid . ' AND tag_id IN (' . $tags . ')');
                        }
                        foreach ($allInputs['resource_type'] as $key => $value) {
                            ResourceTag::create([
                                                    'resource_id' => $request->resid,
                                                    'tag_id' => $value
                                                ]);
                        }
                    }

                    if (isset($allInputs['age_group']) && $allInputs['age_group']  && count($allInputs['age_group']) && isset($allInputs['resource_type']) && $allInputs['resource_type']  && count($allInputs['resource_type'])) {
                        return response()->json(['success' => '1', 'addclass' => 'whitBack'], 200);
                    }
                    else{
                        return response()->json([
                            'success' => '1', 
                            ], 201);
                    }
                }
                elseif($allInputs['form_name'] == 'link2'){
                    if (isset($allInputs['licence']) && !blank($allInputs['licence'])) {
                        $resource = Resource::where('id', $request->resid)->first();
                        $resource->license_type = $request->licence;
                        $resource->save();
                        return response()->json(['success' => '1', 'addclass' => 'whitBack'], 200);
                    }
                    return response()->json(['success' => '1'], 201);
                }
                elseif($allInputs['form_name'] == 'link3'){
                    if ($request->has('terms')) {
                        $_resource = Resource::where('id', $request->resid)->first();

                        $_age_group = Tag::where('tag_group', 'Age Group')->get()->pluck('id')->toArray();
                        $_age_group = implode(",", $_age_group);
                        $_res_tag_age_group = DB::select('SELECT  tag_id FROM resource_tag WHERE resource_id = ' . $request->resid . ' AND tag_id IN (' . $_age_group . ')');


                        $_res_type = Tag::where('tag_group', 'Resource Type')->get()->pluck('id')->toArray();
                        $_res_type = implode(",", $_res_type);
                        $_res_tag_res_type = DB::select('SELECT tag_id FROM resource_tag WHERE resource_id = ' . $request->resid . ' AND tag_id IN (' . $_res_type . ')');

                        if (!blank($_resource->title) && !blank($_resource->description) && !blank($_resource->resource_attachment) && !blank($_resource->preview_attachment) && !blank($_resource->license_type) && !blank($_resource->category_id) && count($_res_tag_age_group) && count($_res_tag_res_type)) {
                            $_resource->resource_status = 'published';
                            $_resource->save();
                            return response()->json(['success' => '1', 'status' => 'Published', 'addclass' => 'whitBack'], 200);
                        }
                        else{
                            return response()->json(['success' => '1', 'pos' => '1'], 201);
                        }

                    }
                    return response()->json(['success' => '1'], 201);
                }
            }
        }
        else{
            
            $resobj = Resource::create([
                                'user_id' => Auth::user()->id,
                                'title' => $request->resource_title,
                                'description' => $request->description,
                                'embed_link' => $request->embed_url,
                                'resource_status' => 'draft'
                            ]);
            $resource = Resource::where('id', $resobj->id)->first();
            if (isset($allInputs['resource_attachment'])) {
                $file = $request->file('resource_attachment');
                $fileName = Auth::user()->id . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'irh_assets/uploads/resource_files';
                $file->move($destinationPath,$fileName);
                $resource->resource_attachment = $fileName;
            }
            if(isset($allInputs['preview_attachment'])){
                $file = $request->file('preview_attachment');
                $fileName = Auth::user()->id . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'irh_assets/uploads/resource_preview';
                $file->move($destinationPath,$fileName);
                $resource->preview_attachment = $fileName;
            }
            elseif(isset($allInputs['generate_preview_attachment'])){
                $resource->preview_attachment = 'dummypreview.png';
            }
            $resource->save();
            $_resource = Resource::where('id', $resobj->id)->first();
            if (!blank($_resource->title) && !blank($_resource->description) && !blank($_resource->resource_attachment) && !blank($_resource->preview_attachment)) {
                return response()->json([
                    'success' => '1', 
                    'resobj' => $resobj, 
                    'addclass' => 'whitBack'], 201);
            }
            else{
                return response()->json([
                    'success' => '1', 
                    'resobj' => $resobj], 201);
            }
        }

    }

    public function editResources(Request $request)
    {
        $filters = Tag::all();
        $resourceCategory = ResourceCategory::all();
        $resource = Resource::where('id', $request->id)->first();
        $resource_tag = ResourceTag::where('resource_id', $request->id)->get()->pluck('tag_id')->toArray();

        return view('dashboard.admin.resources.edit_resource', [
                                'edit' => '0',
                                'filters' => $filters,
                                'resourceCategory' => $resourceCategory,
                                'resource' => $resource,
                                'resource_tag' => $resource_tag
                            ]);
    }


    public function viewReportedUsers(Request $request)
    {
        $allInputs = $request->all();
        $usersObj = DB::table('users')
                            ->select('users.*', 'roles.name as role_name', 'notices_view.total_notice AS notice')
                            ->Join('notices_view', 'users.id', '=', 'notices_view.user_id')
                            ->Join('role_user', 'users.id', '=', 'role_user.user_id')
                            ->Join('roles', 'role_user.role_id', '=', 'roles.id');

        if(isset($allInputs['query']) && !blank($allInputs['query'])){
            $usersObj = $usersObj->where('users.email', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.first_name', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.last_name', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.username', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.position', 'like', ("%" . $allInputs['query'] . "%"));
        }

        $users = $usersObj->orderBy('id', 'ASC')->paginate(30)->withPath('users/pajax');


        return view('dashboard.admin.users.users_list', [
                                    'users' => $users,
                                    'allInputs' => $allInputs,
                                    'rp' => '1'
                                ]);
    }



    public function viewReportedUsersPajax(Request $request)
    {
        if ($request->ajax()) {
            $allInputs = $request->all();

            $usersObj = DB::table('users')
                            ->select('users.*', 'roles.name as role_name', 'notices_view.total_notice AS notice')
                            ->Join('notices_view', 'users.id', '=', 'notices_view.user_id')
                            ->Join('role_user', 'users.id', '=', 'role_user.user_id')
                            ->Join('roles', 'role_user.role_id', '=', 'roles.id');

            if(isset($allInputs['query']) && !blank($allInputs['query'])){
                $usersObj = $usersObj->where('users.email', 'like', ("%" . $allInputs['query'] . "%"))
                                    ->orWhere('users.first_name', 'like', ("%" . $allInputs['query'] . "%"))
                                    ->orWhere('users.last_name', 'like', ("%" . $allInputs['query'] . "%"))
                                    ->orWhere('users.username', 'like', ("%" . $allInputs['query'] . "%"))
                                    ->orWhere('users.position', 'like', ("%" . $allInputs['query'] . "%"));
            }

            $users = $usersObj->orderBy('id', 'ASC')->paginate(30)->withPath('users/pajax');

            return view('dashboard.admin.users.user_pagination', [
                                        'users' => $users,
                                        'allInputs' => $allInputs
                                    ])->render();
        }
    }



    public function delSavedResource(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $saved_resources_array = explode(",", $user->saved_resources);
        $indexCompleted = array_search($request->id, $saved_resources_array);
        unset($saved_resources_array[$indexCompleted]);
        $user->saved_resources = implode(",", $saved_resources_array);
        $user->save();

        return response()->json(['success' => '1'], 200);
    }


    public function ownProfilePassword(Request $request)
    {
        return view('dashboard.users.profile.reset_password');
    }
}
