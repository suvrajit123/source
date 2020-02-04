<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\ResourceBanEvent;
use App\Events\AdminUserContactEvent;
use App\Events\AdminApprovalRequireResPubEvent;
use Illuminate\Support\Facades\Validator;
use App\Resource;
use App\Donation;
use App\Option;
use App\ResourceDownload;
use App\ResourceCategory;
use App\UserRourceVisit;
use App\ResourceFlag;
use App\ResourceLike;
use App\ResourceReview;
use App\Subscriber;
use App\ResourceTag;
use App\Setting;
use App\Tag;
use App\User;
use App\Notice;
use App\MailSetting;
use App\RoleUser;
use App\Testimonial;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Page;
use App\FAQMain;
use App\FAQSub;
use DB;
use Illuminate\Support\Carbon;
use App\Visitor;
use App\UserNotification;
use App\ProfileView;
use Auth;
use Session;
use Hash;

class UserController extends Controller
{


    public function index(Request $request){
        $users = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '!=', '1')->select('users.*')->get();

        $donations = DB::table('donations')->get();
        
        $resources = DB::table('resources')->orderBy('id', 'ASC')->get();

        $resourceDownload = ResourceDownload::where('created_at', '>', Carbon::now()->subDays(30))->get();
        $subscribers = Subscriber::where('created_at', '>', Carbon::now()->subDays(30))->get();
        $visitors = Visitor::where('created_at', '>', Carbon::now()->subDays(30))->get();

        $admins = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', '1')->select('users.*')->get();

        $moderators = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '=', '3')->select('users.*')->get();

        $verifiedUsers = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('users.validate', '=', '1')->select('users.*')->get();

        $userNotifications = UserNotification::where('user_id', Auth::user()->id)->where('status', '=', '0')->get();



        $downloadHistory = DB::select("SELECT resources.*, resd.id AS dhid, resd.created_at AS ddate FROM resources INNER JOIN (SELECT * FROM resource_download WHERE user_id = " . Auth::user()->id . " GROUP BY resource_download.resource_id ORDER BY resource_download.created_at DESC) AS resd ON resources.id = resd.resource_id");

        $userResourceVisit = DB::select("SELECT resources.*, resv.id AS rvid, resv.created_at AS ddate FROM resources INNER JOIN (SELECT * FROM user_resource_visit WHERE user_id = " . Auth::user()->id . " GROUP BY user_resource_visit.resource_id ORDER BY user_resource_visit.created_at DESC) AS resv ON resources.id = resv.resource_id");


        $savedResource = [];
        if (!blank(Auth::user()->saved_resources)) {
            $savedResArray = explode(",", Auth::user()->saved_resources);
            $savedResource = DB::select("SELECT resources.* FROM resources WHERE resources.id IN (" . Auth::user()->saved_resources .")");
        }


        $pendingResource = DB::table('resources')->leftJoin('users', 'resources.user_id', '=', 'users.id')->select('resources.*', 'users.username', 'users.profile_picture')->where('resources.resource_status', 'pending', DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"))->orderBy('id', 'ASC')->get();



        $myResources = DB::table('resources')
                    ->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews_count', 'res_view_count.stars')
                    ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                    ->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')
                    ->where('resources.user_id', "=", Auth::user()->id)
                    ->get();


        $flagedResources = DB::table('resource_flags')->select('resource_flags.resource_id as id','resource_flags.user_id AS flagsBy','resource_flags.reason', 'resource_flags.status', 'resources.title', 'resources.block', 'usr1.username AS uploader', 'usr.username AS flagsUsername', "resources.preview_attachment", "resources.created_at")
                ->join('resources', 'resource_flags.resource_id', '=', 'resources.id')
                ->leftJoin('users', 'resource_flags.user_id', '=', 'users.id')
                ->leftJoin('users as usr', 'resource_flags.user_id', '=', 'usr.id')
                ->leftJoin('users as usr1', 'resources.user_id', '=', 'usr1.id')
                ->leftJoin('resources as rs', 'rs.user_id', '=', 'users.id')
                ->where('resource_flags.status', '=', 'pending')
                ->groupBy('resource_flags.resource_id')->get();

        $followingText = Auth::user()->following;
        $following = [];
        if (!blank($followingText)) {
            $following = User::whereIn('id', explode(",", $followingText))->get();
        }



        $followerText = Auth::user()->followers;
        $followers = [];
        if (!blank($followerText)) {
            $followers = User::whereIn('id', explode(",", $followerText))->get();
        }



        $reportedUsers = DB::table('users')
                            ->select('users.*', 'roles.name as role_name', 'notices_view.total_notice AS notice')
                            ->Join('notices_view', 'users.id', '=', 'notices_view.user_id')
                            ->Join('role_user', 'users.id', '=', 'role_user.user_id')
                            ->Join('roles', 'role_user.role_id', '=', 'roles.id')->get();



        return view('dashboard.index', [
                                        'users' => $users,
                                        'donations' => $donations,
                                        'resources' => $resources,
                                        'resourceDownload' => $resourceDownload,
                                        'subscribers' => $subscribers,
                                        'visitors' => $visitors,
                                        'admins' => $admins,
                                        'moderators' => $moderators,
                                        'verifiedUsers' => $verifiedUsers,
                                        'userNotifications' => $userNotifications,
                                        'downloadHistory' => $downloadHistory,
                                        'myResources' => $myResources,
                                        'userResourceVisit' => $userResourceVisit,
                                        'savedResource' => $savedResource,
                                        'pendingResource' => $pendingResource,
                                        'flagedResources' => $flagedResources,
                                        'following' => $following,
                                        'follower' => $followers,
                                        'reportedUsers' => $reportedUsers,
                                    ]);
    }



    /* Resources Area Start */
    /* Resources List === admin.resources.list */
    
    public function publishedResources(Request $request){
        
        $allInputs = $request->all();
        $resourcesObj = DB::table('resources')->leftJoin('users', 'resources.user_id', '=', 'users.id')->select('resources.*', 'users.username', 'users.profile_picture', DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"))->where('resources.resource_status', 'published');

        if(isset($allInputs['query']) && !blank($allInputs['query'])){
            $resourcesObj = $resourcesObj->where(function($q) use($allInputs){$q->where('resources.title', 'like', ("%" . $allInputs['query'] . "%"))->orWhere('resources.description', 'like', ("%" . $allInputs['query'] . "%"));});
        }

        $resources = $resourcesObj->orderBy('id', 'ASC')->paginate(30)->withPath('published-resources/pajax');

        $likes = null;
        if(!blank(collect($resources->toArray()['data'])->pluck('id')->toArray())){
            $likes = DB::table('resource_likes')->whereIn('resource_id', collect($resources->toArray()['data'])->pluck('id')->toArray())->select(DB::raw("COUNT(resource_likes.id) AS likes"), 'resource_id')->groupBy('resource_id')->get()->toArray();
            $_likes = [];
            if(!blank($likes)){
                foreach ($likes as $key) {
                    $_likes[$key->resource_id] = $key->likes;
                }
            }
            $likes = $_likes;
        }

        
        
        return view('dashboard.resources.admin_published_resource_list', [
                                    'resources' => $resources,
                                    'likes' => $likes,
                                    'allInputs' => $allInputs
                                ]);
    }



    public function publishedResourcesPAjax(Request $request)
    {
        if ($request->ajax()) {
            $allInputs = $request->all();
            $resourcesObj = DB::table('resources')->leftJoin('users', 'resources.user_id', '=', 'users.id')->select('resources.*', 'users.username', 'users.profile_picture', DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"))->where('resources.resource_status', 'published');

            if(isset($allInputs['query']) && !blank($allInputs['query'])){
                $resourcesObj = $resourcesObj->where(function($q) use($allInputs){$q->where('resources.title', 'like', ("%" . $allInputs['query'] . "%"))->orWhere('resources.description', 'like', ("%" . $allInputs['query'] . "%"));});
            }

            $resources = $resourcesObj->orderBy('id', 'ASC')->paginate(30)->withPath('published-resources/pajax');

            $likes = null;
            if(!blank(collect($resources->toArray()['data'])->pluck('id')->toArray())){
                $likes = DB::table('resource_likes')->whereIn('resource_id', collect($resources->toArray()['data'])->pluck('id')->toArray())->select(DB::raw("COUNT(resource_likes.id) AS likes"), 'resource_id')->groupBy('resource_id')->get()->toArray();
                $_likes = [];
                if(!blank($likes)){
                    foreach ($likes as $key) {
                        $_likes[$key->resource_id] = $key->likes;
                    }
                }
                $likes = $_likes;
            }

            
            
            return view('dashboard.resources.published_resource_pagination', [
                                        'resources' => $resources,
                                        'likes' => $likes,
                                        'allInputs' => $allInputs
                                    ])->render();
        }
    }


    public function delPublishedResource(Request $request)
    {
        Resource::where('id', $request->id)->first()->delete();
        return response()->json(['success' => '1'], 200);
    }
    

    public function banPublishedResource(Request $request)
    {
        $prev = null;
        $resource = Resource::find($request->id);
        $prev = $resource->block;
        $resource->block = $resource->block == '1' ? '0' : '1';
        $resource->save();
        $inputs = $request->all();
        if (!isset($inputs['unban'])) {
            $mailData = null;
            if(blank($inputs['message'])){
                $mailData = [
                            'message' => MailSetting::getSettings('ban_mail'), 
                            'user' => $resource->user,
                            'resource' => $resource
                        ];
            }
            else{
                $mailData = [
                            'message' => (MailSetting::getSettings('ban_mail') .'<br>' . $inputs['message']), 
                            'user' => $resource->user,
                            'resource' => $resource
                        ];
            }
            event(new ResourceBanEvent($mailData));
        }
        return response()->json(['success' => '1', 'prev' => $prev], 200);
    }

    public function featuredPublishedResource(Request $request)
    {
        $prev = null;
        $resource = Resource::find($request->id);
        $prev = $resource->isFeatured;
        $resource->isFeatured = $resource->isFeatured == '1' ? '0' : '1';
        $resource->save();
        return response()->json(['success' => '1', 'prev' => $prev], 200);
    }



    /* Pending resource start */



    public function pendingResources(Request $request)
    {
        $allInputs = $request->all();
        $resourcesObj = DB::table('resources')->leftJoin('users', 'resources.user_id', '=', 'users.id')->select('resources.*', 'users.username', 'users.profile_picture')->where('resources.resource_status', 'pending', DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"));

        if(isset($allInputs['query']) && !blank($allInputs['query'])){
            $resourcesObj = $resourcesObj->where(function($q) use($allInputs){$q->where('resources.title', 'like', ("%" . $allInputs['query'] . "%"))->orWhere('resources.description', 'like', ("%" . $allInputs['query'] . "%"));});
        }

        $resources = $resourcesObj->orderBy('id', 'ASC')->paginate(30)->withPath('pending-resources/pajax');

        return view('dashboard.resources.admin_pending_resource_list', [
                                    'resources' => $resources,
                                    'allInputs' => $allInputs
                                ]);
    }



    public function pendingResourcesPAjax(Request $request)
    {
        if ($request->ajax()) {
            $allInputs = $request->all();
            $resourcesObj = DB::table('resources')->leftJoin('users', 'resources.user_id', '=', 'users.id')->select('resources.*', 'users.username', 'users.profile_picture', DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"))->where('resources.resource_status', 'pending');

            if(isset($allInputs['query']) && !blank($allInputs['query'])){
                $resourcesObj = $resourcesObj->where(function($q) use($allInputs){$q->where('resources.title', 'like', ("%" . $allInputs['query'] . "%"))->orWhere('resources.description', 'like', ("%" . $allInputs['query'] . "%"));});
            }

            $resources = $resourcesObj->orderBy('id', 'ASC')->paginate(30)->withPath('pending-resources/pajax');

            return view('dashboard.resources.pending_resource_pagination', [
                                        'resources' => $resources,
                                        'allInputs' => $allInputs
                                    ])->render();
        }
    }


    public function approvePendingResource(Request $request)
    {
        $resource = Resource::find($request->id);
        $resObj = $resource;
        $resource->resource_status = 'published';
        $resource->approved_by = Auth::user()->id;
        $resource->save();
        UserNotification::create([
                'user_id' => $resObj->user_id,
                'message' => ('Your resource has been approved <a href="/resource/' . $resObj->id . '">link</a>.'),
                'status' => '0'
            ]);
        $user = User::where('id', $resObj->user_id)->first();
        $userFollowers = $user->followers;
        if (!blank($userFollowers)) {
            $userFollowersArray = explode(",", $userFollowers);
            foreach ($userFollowersArray as $key => $value) {
                UserNotification::create([
                    'user_id' => $value,
                    'message' => ('New resource has been uploaded by ' . $user->username . ' <a href="/resource/' . $resObj->id . '">link</a>.'),
                    'status' => '0'
                ]);
            }
        }
        return response()->json(['success' => '1', 'data' => 'Resource published!'], 200);
    }

    /* Resources Area End */




    public function userContact(Request $request)
    {
        $mailData = ['user' => User::find($request->id), 'message' => $request->message];
        event(new AdminUserContactEvent($mailData));
        return response()->json(['success' => '1'], 200);
    }



    public function userBlock(Request $request)
    {
        $prev = null;
        $user = User::find($request->id);
        $prev = $user->block;
        $user->block = $user->block == '1' ? '0' : '1';
        $user->save();
        $inputs = $request->all();
        return response()->json(['success' => '1', 'prev' => $prev], 200);
    }


    public function userDelete(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->roles()->detach();
        $user->delete();
        return response()->json(['success' => '1'], 200);
    }


    public function userNotice(Request $request)
    {
        Notice::create([
                            'user_id' => $request->id, 
                            'message' => '', 
                            'status' => '0'
                        ]);
        return response()->json(['success' => '1'], 200);
    }


    /*  Users Area End  */



    /* Flaged Resources Start  */


    public function flagedResources(Request $request)
    {
        $allInputs = $request->all();

        $flagedResourcesObj = DB::table('resource_flags')->select('resource_flags.resource_id as id','resource_flags.user_id AS flagsBy','resource_flags.reason', 'resource_flags.status', 'resources.title', 'resources.block', 'usr1.username AS uploader', 'usr.username AS flagsUsername', "resources.preview_attachment")
                ->join('resources', 'resource_flags.resource_id', '=', 'resources.id')
                ->leftJoin('users', 'resource_flags.user_id', '=', 'users.id')
                ->leftJoin('users as usr', 'resource_flags.user_id', '=', 'usr.id')
                ->leftJoin('users as usr1', 'resources.user_id', '=', 'usr1.id')
                ->leftJoin('resources as rs', 'rs.user_id', '=', 'users.id')
                ->where('resource_flags.status', '=', 'pending')
                ->groupBy('resource_flags.resource_id');

        if(isset($allInputs['query']) && !blank($allInputs['query'])){
            $flagedResourcesObj = $flagedResourcesObj->where('resources.title', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('resources.description', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.email', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.username', 'like', ("%" . $allInputs['query'] . "%"));
        }

        $flagedResources = $flagedResourcesObj->paginate(30)->withPath('flaged-resources/pajax');
        
        return view('dashboard.resources.flag_resource', [
                                        'flagedResources' => $flagedResources,
                                        'allInputs' => $allInputs
                                    ]);
    }


    public function flagedResourcesPAjax(Request $request)
    {
        if ($request->ajax()) {
            $allInputs = $request->all();

        $flagedResourcesObj = DB::table('resource_flags')->select('resource_flags.resource_id as id','resource_flags.user_id AS flagsBy','resource_flags.reason', 'resource_flags.status', 'resources.title', 'resources.block', 'usr1.username AS uploader', 'usr.username AS flagsUsername', "resources.preview_attachment")
                ->join('resources', 'resource_flags.resource_id', '=', 'resources.id')
                ->leftJoin('users', 'resource_flags.user_id', '=', 'users.id')
                ->leftJoin('users as usr', 'resource_flags.user_id', '=', 'usr.id')
                ->leftJoin('users as usr1', 'resources.user_id', '=', 'usr1.id')
                ->leftJoin('resources as rs', 'rs.user_id', '=', 'users.id')
                ->where('resource_flags.status', '=', 'pending')
                ->groupBy('resource_flags.resource_id');

        if(isset($allInputs['query']) && !blank($allInputs['query'])){
            $flagedResourcesObj = $flagedResourcesObj->where('resources.title', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('resources.description', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.email', 'like', ("%" . $allInputs['query'] . "%"))
                                ->orWhere('users.username', 'like', ("%" . $allInputs['query'] . "%"));
        }

        $flagedResources = $flagedResourcesObj->paginate(30)->withPath('flaged-resources/pajax');
        
        return view('dashboard.resources.flag_resource_pagination', [
                                        'flagedResources' => $flagedResources,
                                        'allInputs' => $allInputs
                                    ])->render();
        }
    }


    public function flagedResourceNotice(Request $request)
    {
        $user_id = Resource::find($request->id)->user_id;
        Notice::create([
                            'user_id' => $user_id, 
                            'message' => '', 
                            'status' => '0'
                        ]);
        return response()->json(['success' => '1'], 200);
    }


    public function delFlagedResource(Request $request)
    {
        Resource::where('id', $request->id)->first()->delete();
        return response()->json(['success' => '1'], 200);
    }


    public function remFlagedResource(Request $request)
    {
        //ResourceFlag::where('id', $request->id)->first()->delete();
        DB::delete('DELETE FROM resource_flags WHERE resource_id = ' . $request->id);
        return response()->json(['success' => '1'], 200);
    }


    /* Flaged Resources End  */


    /* Own Section Start */

    public function myResources(Request $request)
    {
        $allInputs = $request->all();
        $resources = DB::table('resources')
                    ->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews_count', 'res_view_count.stars')
                    ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                    ->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')
                    ->where('resources.user_id', "=", Auth::user()->id)
                    ->paginate(10)->withPath('my-resources/pajax');
        
        
        return view('dashboard.resources.my_resources', ['resources' => $resources, 'allInputs' => $allInputs]);
    }


    public function myResourcesPAjax(Request $request)
    {
        if ($request->ajax()) {
            $allParam = $request->all();
            
            $resourcesObj = DB::table('resources')
                    ->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews_count', 'res_view_count.stars')
                    ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                    ->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')
                    ->where('resources.user_id', "=", Auth::user()->id);

            if (isset($allParam['query']) && !blank($allParam['query'])){
                $resourcesObj = $resourcesObj->where(function($q) use($allParam){$q->where('resources.title', 'like', ("%" . $allParam['query'] . "%"))->orWhere('resources.description', 'like', ("%" . $allParam['query'] . "%"));});
            }
            $resources = $resourcesObj->paginate(10)->withPath('my-resources/pajax');
        
        
        return view('dashboard.resources.my_resources_pagination', ['resources' => $resources, 'allInputs' => $allParam])->render();
        }
    }


    public function delMyResources(Request $request)
    {
        Resource::where('id', $request->id)->first()->delete();
        return response()->json(['success' => '1'], 200);
    }




    public function remFollwing(Request $request)
    {
        $selfRow = User::where('id', Auth::user()->id)->first();
        $selfFollowing = explode(",", $selfRow->following);
        $indexCompleted = array_search($request->id, $selfFollowing);
        unset($selfFollowing[$indexCompleted]);
        $selfRow->following = implode(",", $selfFollowing);
        $selfRow->save();

        $userFollowerRow = User::where('id', $request->id)->first();
        $userFollower = explode(",", $userFollowerRow->followers);
        $indexCompleted = array_search(Auth::user()->id, $userFollower);
        unset($userFollower[$indexCompleted]);
        $userFollowerRow->followers = implode(",", $userFollower);
        $userFollowerRow->save();


        return response()->json(['success' => '1'], 200);
    }


    public function userVerify(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->validate = '1';
        $user->save();
        return response()->json(['success' => '1'], 200);
    }


    public function notifications(Request $request)
    {
        $notifications = UserNotification::where('user_id', Auth::user()->id)->where('status', '0')->get();
        DB::update('UPDATE notifications SET status = 1 WHERE user_id = ' . Auth::user()->id);
        return view('dashboard.static.notifications', ['notifications' => $notifications]);
    }




    public function downloadHistoryList(Request $request)
    {
        $downloadHistory = DB::table('resource_download')->leftJoin('resources', 'resource_download.resource_id', '=', 'resources.id')->where('resource_download.user_id', '=', Auth::user()->id)->select('resources.preview_attachment', 'resource_download.*')->orderBy('resource_download.id', 'DESC')->get();

        
        return view('dashboard.static.download_history_list', ['downloadHistory' => $downloadHistory]);
    }


    public function downloadHistoryRemoveById(Request $request)
    {
        ResourceDownload::where('id', $request->id)->where('user_id', Auth::user()->id)->first()->delete();
        return response()->json(['success' => '1'], 200); 
    }


    public function downloadHistoryRemoveByBatch(Request $request)
    {
        if ($request->id == '1') {
            DB::delete('DELETE FROM resource_download WHERE created_at >= DATE_SUB(now(),interval 1 hour) AND user_id = ' . Auth::user()->id);
        }
        elseif($request->id == '2'){
            DB::delete('DELETE FROM resource_download WHERE created_at >= DATE_SUB(now(),interval 1 DAY) AND user_id = ' . Auth::user()->id);
        }
        elseif($request->id == '2'){
            DB::delete('DELETE FROM resource_download WHERE created_at >= DATE_SUB(now(),interval 7 DAY) AND user_id = ' . Auth::user()->id);
        }
        elseif($request->id == '2'){
            DB::delete('DELETE FROM resource_download WHERE created_at >= DATE_SUB(now(),interval 28 DAY) AND user_id = ' . Auth::user()->id);
        }
        else{
            DB::delete('DELETE FROM resource_download WHERE user_id = ' . Auth::user()->id);
        }
        return response()->json(['success' => '1'], 200); 
    }


    public function viewingHistoryList(Request $request)
    {
        $viewingHistory = DB::table('user_resource_visit')->leftJoin('resources', 'user_resource_visit.resource_id', '=', 'resources.id')->where('user_resource_visit.user_id', '=', Auth::user()->id)->select('resources.preview_attachment', 'user_resource_visit.*')->orderBy('user_resource_visit.id', 'DESC')->get();

        
        return view('dashboard.static.viewing_history_list', ['viewingHistory' => $viewingHistory]);
    }


    public function viewingHistoryRemoveById(Request $request)
    {
        UserRourceVisit::where('id', $request->id)->first()->delete();
        return response()->json(['success' => '1'], 200); 
    }


    public function viewingHistoryRemoveByBatch(Request $request)
    {
        if ($request->id == '1') {
            DB::delete('DELETE FROM user_resource_visit WHERE created_at >= DATE_SUB(now(),interval 1 hour) AND user_id = ' . Auth::user()->id);
        }
        elseif($request->id == '2'){
            DB::delete('DELETE FROM user_resource_visit WHERE created_at >= DATE_SUB(now(),interval 1 DAY) AND user_id = ' . Auth::user()->id);
        }
        elseif($request->id == '2'){
            DB::delete('DELETE FROM user_resource_visit WHERE created_at >= DATE_SUB(now(),interval 7 DAY) AND user_id = ' . Auth::user()->id);
        }
        elseif($request->id == '2'){
            DB::delete('DELETE FROM user_resource_visit WHERE created_at >= DATE_SUB(now(),interval 28 DAY) AND user_id = ' . Auth::user()->id);
        }
        else{
            DB::delete('DELETE FROM user_resource_visit WHERE user_id = ' . Auth::user()->id);
        }
        return response()->json(['success' => '1'], 200); 
    }



    public function viewSavedResource(Request $request)
    {
        $allParam = $request->all();
        $resources = null;

        $saved_resource_text = Auth::user()->saved_resources;
        if (blank($saved_resource_text)) {
             $filters = Tag::all();
            return view('dashboard.static.saved_resource',[
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
            return view('dashboard.static.saved_resource',[
                                    'resources' => $resources,
                                    'allParam' => $allParam,
                                    'filters' => $filters]);
        }
    }



    public function createResources(Request $request)
    {
        $filters = Tag::all();
        $resourceCategory = ResourceCategory::all();
        return view('dashboard.resources.edit_resource', [
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
                            $_resource->resource_status = Auth::user()->roles[0]->name == 'user' ? 'pending' : 'published';
                            $_resource->save();
                            
                            event(new AdminApprovalRequireResPubEvent(Auth::user(), $_resource));
                            if (Auth::user()->roles[0]->name == 'user') {
                                $userAdMo = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '1')->orWhere('role_user.role_id', '3')->select('users.*')->get();
                                foreach ($userAdMo as $user) {
                                    UserNotification::create([
                                                    'user_id' => $user->id,
                                                    'message' => ('New resource submited, url = <a href="/pending-resource/' . $_resource->id . '" target="_blank">New Resource</a>'),
                                                    'status' => '0'
                                                ]);
                                }
                            }
                            return response()->json(['success' => '1', 'status' => (Auth::user()->roles[0]->name == 'user' ? 'pending' : 'published'), 'addclass' => 'whitBack'], 200);
                        }
                        else{
                            return response()->json(['success' => '1', 'pos' => '1','status' => 'Pending'], 201);
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

        return view('dashboard.resources.edit_resource', [
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

    public function ownProfile(Request $request)
    {
        $countries = DB::table('countries')->get();
        return view('dashboard.users.profile.profile', ['countries' => $countries]);
    }


    public function updateProfile(Request $request)
    {
        $allInputs = $request->all();
        $user = User::where('id', Auth::user()->id)->first();
        $user->first_name = $allInputs['first_name'];
        $user->last_name = $allInputs['last_name'];
        $user->about_me = $allInputs['about_me'];
        $user->position = $allInputs['user_position'];
        $user->working_in = $allInputs['cur_work'];
        $user->subjects = $allInputs['spl_subj'];
        $user->country = $allInputs['country'];
        $user->hobbies = $allInputs['hobbies'];
        $user->private_info = $allInputs['private_info'];
        $user->linkedin = $allInputs['linkedin'];
        $user->instagram = $allInputs['instagram'];
        $user->twiter = $allInputs['twiter'];
        $user->fb = $allInputs['fb'];
        $user->new_social = $allInputs['new_social'];

        if (isset($allInputs['profile_picture'])) {
            $file = $request->file('profile_picture');
            $fileName = Auth::user()->id . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'irh_assets/uploads/profile_pictures';
            $file->move($destinationPath,$fileName);
            $user->profile_picture = $fileName;
        }
        $user->save();
        Session::flash('message', "User profile updated!");
        return redirect()->back();
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


    public function ownProfilePasswordSave(PasswordResetRequest $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->current_password, $user->password)) { 
           $user->fill([
            'password' => Hash::make($request->new_password)
            ])->save();

            Session::flash('success', 'Password changed');
            return redirect()->back();

        } else {
            Session::flash('error', 'Password does not match');
            return redirect()->back();
        }
    }
}
