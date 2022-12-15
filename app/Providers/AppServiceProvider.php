<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Model\Setting;
use App\Model\Permission;
use App\Model\ServiceBuy;
use App\Model\ProvinceCity;
use App\Model\About;
use App\Model\Meta;
use App\Model\Visit;
use App\Model\Network;
use App\Model\WorkTimesheet;
use App\Model\TimesheetCircle;
use App\User;
use Illuminate\Support\Facades\Cookie;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(Request $request)
    {
        $this->url = $request->fullUrl();
        Blade::directive('item', function ($name) {
            return "<?php echo $name ?>";
        });

        Schema::defaultStringLength(191);
        Carbon::setLocale('fa');
        
        view()->composer('layouts.admin', function ($view) {
            if (auth()->user()->role_id==1) {
                $permission = 'کاربران,اعلانات,فعالیتها,محتوا,تنظیمات,مدیر';
            } else {
                $permission = Permission::where('name', auth()->user()->role_id )->first('access');
                if ($permission) {
                    $permission = $permission->access;
                }
            }
            $allUsers = User::all(['id','name']);
            $setting = Setting::first();
            $view->with('permission', $permission);
            $view->with('setting', $setting);
            $view->with('allUsers', $allUsers);
        });
        view()->composer('layouts.user', function ($view) {
            //visit
            $ip = getenv('HTTP_CLIENT_IP') ?:
                getenv('HTTP_X_FORWARDED_FOR') ?:
                    getenv('HTTP_X_FORWARDED') ?:
                        getenv('HTTP_FORWARDED_FOR') ?:
                            getenv('HTTP_FORWARDED') ?:
                                getenv('REMOTE_ADDR');
            $date=date('Y-m-d');
            $visit_old=Visit::whereDate('created_at','=',$date)->where('ip',$ip)->first();
            if($visit_old)
            {
                $visit_old->view+=1;
                $visit_old->update();
            }
            else {
                $visit=new Visit();
                $visit->ip=$ip;
                $visit->view=1;
                $visit->save();
            }
            $seo = Meta::where('url', $this->url)->first();
            if (is_null($seo)) {
                $seo = Meta::where('url', $this->url . '/')->first();
                if (is_null($seo)) {
                    $seo = Meta::where('url', explode('?', $this->url)[0])->first();
                    if (is_null($seo)) {
                        $seo = Meta::where('url', explode('?', $this->url)[0] . '/')->first();
                    }
                }
            }
            $setting=Setting::first();
            if (!is_null($seo)) {
                $titleSeo = $seo->title;
                $keywordsSeo = $seo->key_word;
                $descriptionSeo = $seo->description;
            }
            else {
                $titleSeo = $setting->title;
                $keywordsSeo = $setting->keyword;
                $descriptionSeo = $setting->description;
            }
            
            $view
                ->with('setting', $setting)
                ->with('titleSeo', $titleSeo)
                ->with('keywordsSeo', $keywordsSeo)
                ->with('descriptionSeo', $descriptionSeo);
                // ->with('ServiceCats', $ServiceCat);
            if (Cookie::get('basket') != null){
                $view->with('BasketCount', count(json_decode(Cookie::get('basket'))));
            }else {
                $view->with('BasketCount', '');
            }
        });
        
        view()->composer('user.master', function ($view) {
            $setting = Setting::first(['title','icon_site']);
            $today   = Carbon::now()->format('Y-m-d');
            if (auth()->user()) {
                $runningJob = WorkTimesheet::where('startDate', $today)->where('user_id', auth()->user()->id)->where('status', 'doing')->first(['id','type_id','type','startTime']);
                if ($runningJob) {
                    $timeNow   = (intval(Carbon::now()->format('H')) * 60 ) + intval(Carbon::now()->format('i'));
                    $startTime = (intval(substr( $runningJob->startTime , 0 , 2 )) * 60) + intval(substr( $runningJob->startTime , 3 , 2 )) + $runningJob->pausedMinutes($runningJob);
                    $runningJob->startTime  = $timeNow - $startTime;
                    $view->with('runningJob', $runningJob);
                }
            }
            $view->with('setting', $setting);
        });
        view()->composer('includes.header', function ($view) {
            $setting = Setting::first();
            // $view->with('notification', Notification::where('user_id', auth()->user()->id)->where('status', 'pending')->count());
            $view->with('setting', $setting);
            $view->with('network', Network::where('status', 'active')->orderBy('sort')->get());
        });
        view()->composer('includes.head', function ($view) {
            $setting = Setting::first();
            $view->with('setting', $setting);
        });
        view()->composer('auth.login', function ($view) {
            $setting = Setting::first();
            $about = About::first();
            $view->with('setting', $setting);
            $view->with('about', $about);
        });
    }
    
}
