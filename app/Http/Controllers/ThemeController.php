<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResourceCategory;
use App\Tag;
use App\Resource;
use App\ResourceReview;
use App\User;
use App\ResourceLike;
use App\ResourceTag;
use App\ResourceFlag;
use App\Subscriber;
use App\Donation;
use App\Testimonial;
use App\ResourceDownload;
use App\UserNotification;
use App\UserRourceVisit;
use App\Mail\ContactUs;
use App\Mail\ContactAuthorMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use Session;
use App\Events\ContactUsEvent;
use DB;
use Illuminate\Support\Carbon;
use App\FAQMain;
use App\FAQSub;
use App\ProfileView;
use App\Http\Requests\ContactFormRequest;

class ThemeController extends Controller
{
    private $resourceModel = null;
    private $resourceCategoryModel = null;
    private $resourceReviewModel = null;
    private $tagModel = null;
    private $userModel = null;
    private $resourceLikeModel = null;
    private $subscriberModel = null;
    private $resourceFlagModel = null;
    private $donationModel = null;
    private $testimonial = null;

    public function __construct()
    {
        $this->resourceModel = new Resource();
        $this->resourceCategoryModel = new ResourceCategory();
        $this->resourceReviewModel = new ResourceReview();
        $this->tagModel = new Tag();
        $this->userModel = new User();
        $this->resourceLikeModel = new ResourceLike();
        $this->subscriberModel = new Subscriber();
        $this->resourceFlagModel = new ResourceFlag();
        $this->donationModel = new Donation();
        $this->testimonialModel = new Testimonial();
    }

    /**
     *
     * Shows Front-End of Website
     *
     */
    public function index()
    {
    	$categories         = $this->resourceCategoryModel->allCategories();
        $featuredResources  = $this->resourceModel->featuredResources();
        $newResources       = $this->resourceModel->newResources();
        $testimonials       = $this->testimonialModel->allTestimonials();
    	return view('home')->withCategories($categories)->withFeatured($featuredResources)->with('new_resources',$newResources)->withTestimonials($testimonials);
    }


    /**
     *
     * Shows list of all resources
     *
     */
    public function resources()
    {
        $resources      = $this->resourceModel->publishedResources(20);
        $resource_types = $this->tagModel->resourceTypeTags();
        $age_groups     = $this->tagModel->ageGroupTags();
        return view('resources')->withResources($resources)->with('resource_types',$resource_types)->with('age_groups',$age_groups);
    }

    /**
     *
     * Resources filtered by category
     *
     */
    public function resourcesByCategory($category, $rid = null)
    {
        $savedResources = [];
        $category = ResourceCategory::where('id', $category)->first();

        $resource_types = $this->tagModel->resourceTypeTags();
        $age_groups     = $this->tagModel->ageGroupTags();
        if (!blank($category)) {
            if (Auth::user()) {
                if (!blank(Auth::user()->saved_resources)) {
                    $savedResources = explode(",", Auth::user()->saved_resources);
                }
            }

            $resourcesObj = DB::table('resources')
                                ->select('resources.*', 'res_like_count.res_likes as reslikes', 'users.id AS userid', DB::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))
                                ->join('users', 'resources.user_id', '=', 'users.id')
                                ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                                ->where('resources.category_id', '=', $category->id)
                                ->where('resource_status', '=', 'published')
                                ->orderBy('resources.id', 'DESC');
            $lastid = "";
            if (!blank($rid)) {
                $resources = $resourcesObj->where('resources.id', '<', $rid)->limit(4)->get();

                if (count($resources)) {
                    $lastid = collect($resources)->last()->id;
                }
                return view('resources_cat_pagination',['resources' => $resources, 'category' => $category->title, 'savedResources' => $savedResources, 'lastid' => $lastid, 'category_id' => $category->id])->render();
            }

            $resources = $resourcesObj->limit(4)->get();

            if (count($resources)) {
                $lastid = collect($resources)->last()->id;
            }
            return view('resources-by-cat',[
                                'resources' => $resources, 
                                'category' => $category->title, 
                                'savedResources' => $savedResources, 
                                'resource_types' => $resource_types,
                                'age_groups' => $age_groups,
                                'lastid' => $lastid, 
                                'category_id' => $category->id]);
            
        }
        else{
            return view('resources-by-cat',[
                                'resources' => [], 
                                'category' => $category->title, 
                                'savedResources' => [], 
                                'lastid' => "", 
                                'resource_types' => $resource_types,
                                'age_groups' => $age_groups,
                                'category_id' => $category->id]);
        }
    }

    /**
     *
     * Resources filtered by category
     *
     */
    public function resourcesByTag($tag, $rid = null)
    {
        $savedResources = [];
        $resid = ResourceTag::where('tag_id', $tag)->groupBy('resource_id')->get()->pluck('resource_id')->toArray();
        $tag = Tag::where('id', $tag)->first();
        $resource_types = $this->tagModel->resourceTypeTags();
        $age_groups     = $this->tagModel->ageGroupTags();
        if (blank($resid)) {
            return view('resources-by-cat',[
                                'resources' => [], 
                                'tag' => $tag->name, 
                                'savedResources' => [], 
                                'lastid' => "", 
                                'tag_id' => $tag->id]);
        }
        else{
            if (Auth::user()) {
                if (!blank(Auth::user()->saved_resources)) {
                    $savedResources = explode(",", Auth::user()->saved_resources);
                }
            }

            $resourcesObj = DB::table('resources')
                                ->select('resources.*', 'res_like_count.res_likes as reslikes', 'users.id AS userid', DB::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))
                                ->join('users', 'resources.user_id', '=', 'users.id')
                                ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                                ->whereIn('resources.id', $resid)
                                ->where('resource_status', '=', 'published')
                                ->orderBy('resources.id', 'DESC');
            $lastid = "";
            if (!blank($rid)) {
                $resources = $resourcesObj->where('resources.id', '<', $rid)->limit(1)->get();

                if (count($resources)) {
                    $lastid = collect($resources)->last()->id;
                }
                return view('resources_cat_pagination',['resources' => $resources, 'tag' => $tag->name, 
                                'savedResources' => $savedResources, 
                                'resource_types' => $resource_types,
                                'age_groups' => $age_groups,
                                'lastid' => $lastid, 
                                'tag_id' => $tag->id])->render();
            }

            $resources = $resourcesObj->limit(1)->get();

            if (count($resources)) {
                $lastid = collect($resources)->last()->id;
            }
            return view('resources-by-cat',[
                                'resources' => $resources, 
                                'tag' => $tag->name, 
                                'resource_types' => $resource_types,
                                'age_groups' => $age_groups,
                                'savedResources' => $savedResources, 
                                'lastid' => $lastid, 
                                'tag_id' => $tag->id]);
        }
    }



    /**
     *
     * Show details for single resource
     *
     */
    public function singleResource(Resource $resource)
    {
        if($resource->resource_status !== 'published')
            abort(404);
        $resource->increment('views');
        
        if (Auth::user()) {
            $userRourceVisit = UserRourceVisit::where('resource_id', $resource->id)->where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->first();
            if (blank($userRourceVisit)) {
                UserRourceVisit::create(['resource_id' => $resource->id, 'user_id' => Auth::user()->id]);
            }
        }

        $relatedResources = $this->resourceModel->relatedResources($resource);
        $resources = $this->resourceModel->getResourceByUserId($resource->user_id);

        return view('single-resource')
                        ->withResource($resource)
                        ->withRelated($relatedResources)
                        ->withResources($resources);
    }


    public function singlePendingResource(Resource $resource)
    {
        if($resource->resource_status !== 'pending')
            abort(404);
        $resource->increment('views');
        
        if (Auth::user()) {
            $userRourceVisit = UserRourceVisit::where('resource_id', $resource->id)->where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->first();
            if (blank($userRourceVisit)) {
                UserRourceVisit::create(['resource_id' => $resource->id, 'user_id' => Auth::user()->id]);
            }
        }

        $relatedResources = $this->resourceModel->relatedResources($resource);
        $resources = $this->resourceModel->getResourceByUserId($resource->user_id);

        return view('pending_resource')
                        ->withResource($resource)
                        ->withRelated($relatedResources)
                        ->withResources($resources);
    }


    /**
     *
     * Author Profile
     *
     */
    public function authorProfile(User $user, Request $request)
    {
        $allParam = $request->all();
        $resources = null;
        if(ProfileView::getProfileViewed($user->id) == '0'){
            ProfileView::create(['user_id' => $user->id, 'profile_view' => '1']);
        }
        else{
            DB::table('profile_view')->increment('profile_view');
        }


        if (isset($allParam['search']) && !blank($allParam['search'])) {
            $resources = DB::table('resources')
                    ->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews')
                    ->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')
                    ->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')
                    ->where('resources.user_id', "=", $user->id)
                    ->where(function($q) use($allParam){$q->where('resources.title', 'like', ("%" . $allParam['search'] . "%"))->orWhere('resources.description', 'like', ("%" . $allParam['search'] . "%"));})
                    ->get();

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
                ->where('resources.user_id', "=", $user->id)
                ->when(!blank($searchArray), function ($q) use($searchArray){
                    return $q->whereIn('resources.id', DB::table('resource_tag')->select("resource_id")->whereIn('tag_id', $searchArray)->groupBy('resource_id')->get()->pluck('resource_id')->toArray());
                })->orderBy($sortBy[0], $sortBy[1])->get();

        }
        else{
            $resources = DB::table('resources')->select(DB::raw("IF(resources.preview_attachment = null, CONCAT('irh_assets/uploads/resource_preview/' , resources.preview_attachment),'irh_assets/images/dummypreview.png') prev"),'resources.*', 'res_like_count.res_likes AS likes', 'res_view_count.res_reviews AS reviews')->leftJoin('res_like_count', 'resources.id', '=', 'res_like_count.resource_id')->leftJoin('res_view_count', 'resources.id', '=', 'res_view_count.resource_id')->where('resources.user_id', "=", $user->id)->get();
        }
        $filters = Tag::all();

        $rolename = DB::table('roles')->join('role_user', 'roles.id', 'role_user.role_id')->select('roles.name')->where('role_user.user_id', $user->id)->first()->name;
        

        return view('author',[
                                'author' => $user,
                                'rolename' => $rolename,
                                'resources' => $resources,
                                'allParam' => $allParam,
                                'filters' => $filters]);
    }


    /**
     *
     * Send mail to author
     *
     */
    public function authorProfile__SendMail(Request $request)
    {
        $author = User::find($request->author_id);
        if(!$author)
        {
            abort(404,'Author Not Found');
        }
        Mail::to($author)->send(new ContactAuthorMail($request));
        Session::flash('success','Message Sent Successfully!');
        return redirect()->back();
    }


    /**
     *
     * Add review to specific resource
     *
     */
    public function addReviewToResource(Request $request)
    {
        $this->resourceReviewModel->addReview($request);
        $resource = Resource::where('id', $request->resource_id)->first();
        UserNotification::create([
            'user_id' => $resource->user_id,
            'message' => ('<a href="/resource/author/' . Auth::user()->id . '">' . Auth::user()->username . '</a> comments on your resource'),
            'status' => '0'
        ]);
        return redirect()->back();
    }

    /**
     *
     * Updates a review from specific resource
     *
     */
    public function updateReviewFromResource(ResourceReview $review,Request $request)
    {
        $this->resourceReviewModel->updateReview($review,$request);
        return redirect()->back();
    }

    /**
     *
     * Status 0 a review from resource by admin
     *
     */
    public function deleteReviewFromResource(ResourceReview $review)
    {
        $this->resourceReviewModel->deleteReview($review);
        return redirect()->back();
    }



    /**
     *
     * Filters the resources
     *
     */
    public function filteredResources(Request $request)
    {
        $category = ResourceCategory::all();
        $filtered_resources = $this->resourceModel->filterResources($request);
        $resource_types     = $this->tagModel->resourceTypeTags();
        $age_groups         = $this->tagModel->ageGroupTags();

        return view('filtered-resources', [
                                'resources' => $filtered_resources,
                                'resource_types' => $resource_types,
                                'age_groups' => $age_groups,
                                'category' => $category
                            ]);
    }

    /**
     *
     * Shows Support Us Page
     *
     */

    public function supportUs()
    {
        return view('supportus');
    }

    /**
     *
     * Make One-Time Donation
     *
     */
    public function supportUsDonationOneTime(Request $request)
    {
        /*echo "<pre>";
        print_r($request->all());
        die();*/
        $this->donationModel->donateOneTime($request);
        try {
            UserNotification::create([
                'user_id' => Auth::user()->id,
                'message' => ('Donation confirmed! Jazakallahu Khairan for your kind donation may Allah reward you in abundance and accept this from you.'),
                'status' => '0'
            ]);
            
        } catch (\Exception $e) {
            
        }
        Session::flash('success','JazakAllah! You have donated successfully.');
        return redirect()->back();
    }

    /**
     *
     * Make Monthly Donation
     *
     */
    public function supportUsDonationMonthly(Request $request)
    {
        $this->donationModel->monthlySubscription($request);
        try {
            UserNotification::create([
                'user_id' => Auth::user()->id,
                'message' => ('Donation confirmed! Jazakallahu Khairan for your kind donation may Allah reward you in abundance and accept this from you.'),
                'status' => '0'
            ]);
            
        } catch (\Exception $e) {
            
        }
        Session::flash('success','JazakAllah! You have been subscribed to monthly donation plan');
        return redirect()->back();
    }


    /**
     *
     * Show list of all saved Resources
     *
     */
    public function savedResources()
    {
        $saved_resources = $this->resourceModel->loggedInUserSavedResources();
        return view('saved-resources')->withResources($saved_resources);
    }


    /**
     *
     * Save a new resource against some user
     *
     */
    public function saveResource(Request $request)
    {
        $this->resourceModel->saveResourceAgainstLoggedInUser($request->resource);
        return response()->json(['success'=>'success'],200);
    }

    /**
     *
     * Unsave a specific resource against some user
     *
     */
    public function unSaveResource(Resource $resource)
    {
        $this->resourceModel->unSaveResourceFromLoggedInUser($resource);
        return redirect()->back();
    }


    /**
     *
     * Like a specific resource
     *
     */
    public function likeResource(Request $request)
    {
        $this->resourceLikeModel->likeResourceAgainstLoggedInUser($request->resource);
        $resource = Resource::where('id', $request->resource)->first();
        UserNotification::create([
            'user_id' => $resource->user_id,
            'message' => ('<a href="/resource/author/' . Auth::user()->id . '">' . Auth::user()->username . '</a> likes your resource'),
            'status' => '0'
        ]);

        return response()->json(['success'=>'success'],200);
    }


    /**
     *
     * Download attachment for specific resource
     *
     */
    public function downloadResource(Resource $resource)
    {
        if($resource->resource_attachment !== null)
        {
            $resourceDownload = ResourceDownload::where('resource_id', $resource->id)->whereDate('created_at', Carbon::today())->first();

            if (!blank($resourceDownload)) {
                $resourceDownload->increment('downloads');
            }
            else{
                ResourceDownload::create([
                    'user_id'   =>  Auth::user()->id,
                    'resource_id'   =>  $resource->id,
                    'downloads'     =>  1
                ]);
            }

            $resource->increment('downloads');
            $path = public_path('irh_assets/uploads/resource_files/'.$resource->resource_attachment);
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            return response()->download($path);
        }
        else
        {
            return redirect()->back();
        }
    }


    /**
     *
     * Flag a specific resource
     *
     */
    public function flagResource(Request $request)
    {
        $this->resourceFlagModel->flagResource($request);
        Session::flash('success','You have flagged this resource. We will review it soon');
        return redirect()->back();
    }


    /**
     *
     * Shows contact us page
     *
     */
    public function contactus()
    {
        $faqMain = FAQMain::orderBy('id')->get();
        $faqSub = FAQSub::orderBy('id');
        
        $faqMain = collect($faqMain)->map(function($ar){
            $ar->sub = DB::table('faq_sub')->orderBy('id')->where('faq_main_id', $ar['id'])->get()->toArray();
            return $ar;
        });

        return view('contactus', ['faqMain' => $faqMain]);
    }

    /**
     *
     * Send mail to Islamic Resource Hub Admin
     *
     */
    public function sendContactMail(ContactFormRequest $request)
    {
      /*
        Mail::to('support@islamicresourcehub.com')->send(new ContactUs($request));
        Session::flash('success','Message Sent Successfully!');
        return redirect()->back();
        */
        /*Mail::to('olindo.testing@gmail.com')->send(new ContactUs($request));
        Session::flash('success','Message Sent Successfully!');
        return redirect()->back();*/
        event(new ContactUsEvent($request));
       /* Mail::to('olindo.testing@gmail.com')->send(new ContactUs($request));*/
        Session::flash('success','Message Sent Successfully!');
        return redirect()->back();
    }


    public function authorFollow($id)
    {
        if (Auth::user()) {
            $user = User::where('id', Auth::user()->id)->first();
            $userFollowing = explode(",", $user->following);
            if (blank($user->following)) {
                $user->following = $id;
                $user->save();
            }
            else{
                $user->following = implode(",", array_merge($userFollowing, [$id]));
                $user->save();
            }

            $author = User::where('id', $id)->first();
            $authorFollowers = explode(",", $author->followers);
            if (blank($author->followers)) {
                $author->followers = Auth::user()->id;
                $author->save();
            }
            else{
                $author->followers = implode(",", array_merge($authorFollowers, [Auth::user()->id]));
                $author->save();
            }
            UserNotification::create([
                                        'user_id' => $author->id,
                                        'message' => ('<a href="/resource/author/' . $user->id . '">' . $user->username . '</a> follows you'),
                                        'status' => '0'
                                    ]);
            return response()->json(['success' => '1'], 200);
        }
    }

}
