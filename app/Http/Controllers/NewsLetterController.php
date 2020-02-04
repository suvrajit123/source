<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use App\Resource;
use Newsletter;
use Session;
use Carbon\Carbon;

class NewsLetterController extends Controller
{
	private $subscriberModel = null;

	public function __construct()
	{
		$this->subscriberModel = new Subscriber();
	}

    public function subscribe(Request $request)
    {
		$customMessages = ['email.unique' => 'You have already subscribed to our newsletter' ];
        $request->validate(['email'=>'required|unique:subscribers'],$customMessages);
        $this->subscriberModel->subscribeToNewsletter($request->email);
        //Newsletter::subscribe($request->email,[],'personal');
        //Session::flash('success',);
        return response()->json(['message' => 'Jazakallahu Khairan for subscribing! You will receive an email with a link to confirm your subscription. If you don’t get it, please try again.'], 201);
    }


    public function subscribers()
    {
        $subscribers = Subscriber::all();
        return view('dashboard.subscribers')->withSubscribers($subscribers);
    }

    public function unsubscribe(Subscriber $subscriber)
    {
        Newsletter::unsubscribe($subscriber->email,'subscribersList');
        $subscriber->delete();
        Session::flash('success','Newsletter Subscription Removed Successfully!');
        return redirect()->back();
    }

    public function monthyTopResourcesNewsleter()
    {
         //public function createCampaign(
            // string $fromName,
            // string $replyTo,
            // string $subject,
            // string $html = '',
            // string $listName = '',
            // array $options = [],
            // array $contentOptions = [])

        $topResources = Resource::where('resource_status','published')->whereMonth('created_at', '=', date('m'))->orderBy('views','desc')->limit(3)->get();
    
        $html = '
                    <h3>Resources of the Month</h3>
                    <br/>
                    <ul>
                ';
        foreach($topResources as $res)
        {
            $html .= '<li><a href="'.route("theme.singleresource",$res).'">'.$res->title.'</a></li>';
        }
        $html .= '</ul>';

        Newsletter::createCampaign('IRH','viralwebbs@gmail.com','Monthly Top 3 Resources',$html,'registeredUsersList');

        dd('created');
    }

}
