<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Analytics;
use Spatie\Analytics\Period;
use Auth;
use Carbon\Carbon;
use App\Donation;
use App\Subscriber;

class DashboardController extends Controller
{
   /**
     * Create a new controller instance.
     *
     * @return void
     */
   public function __construct()
   {
        $this->middleware('auth');
   }

    /**
     *
     * Shows admin dashboard
     *
     */
    public function index()
    {
    	return view('dashboard.index');
    }

    /**
     *
     * Show form for updating password
     *
     */
    public function password()
    {
        return view('dashboard.password');
    }

    /**
     *
     * Update password
     *
     */
    public function updatePassword(Request $request)
    {
            $this->validate($request,[
                'current_password'      =>  'required',
                'password'              => ['required', 'string', 'min:6', 'confirmed']
             ]);

            $user = Auth::user();

            if (Hash::check($request->current_password, $user->password))
            {
                $user->password = Hash::make($request->password);
                if($user->save())
                {
                    $this->success('Password Updated Successfully!');
                }
                else
                {
                    $this->error();
                }
            }
            else
            {
                $this->error('Current password is not correct');
            }
            return redirect()->back();
    }

    public function getStatistics(Request $request)
    {
        if($request->type == 'monthly')
        {
            $analyticsMonthly = Analytics::fetchTotalVisitorsAndPageViews(Period::days(30))->groupBy(function (array $visitorStatistics) {
             return $visitorStatistics['date']->format('d-M');
             })->map(function ($visitorStatistics, $dayMonth) {
                 list($day, $month) = explode('-', $dayMonth);
                 return ['date' => "{$day}-{$month}", 'visitors' => $visitorStatistics->sum('visitors'), 'pageViews' => $visitorStatistics->sum('pageViews')];
             })->values();
            return response()->json(['stats'=>$analyticsMonthly],200);
        }
        elseif($request->type == 'browser')
        {
            $topBrowsers = Analytics::fetchTopBrowsers(Period::days(30));
            return response()->json(['browsers'=>$topBrowsers],200);
        }

    }

    public function donations()
    {
        $donations = Donation::latest()->get();
        return view('dashboard.donations')->withDonations($donations);
    }

}
