<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon; //untuk datetime
use App\Models\Post;
use App\Models\User;
use App\Models\Slider;
use App\Models\Category;
use App\Models\PostView;
use Illuminate\Support\Facades\DB; //untuk query database

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //count categories
        $categories = Category::count();

        //count posts
        $posts = Post::count();

        //count sliders
        $sliders = Slider::count();

        //count users
        $users = User::count();

        /**
         * get views posts at 30 days
         */
        $post_views = PostView::select([
            //count id
            DB::raw('count(id) as count '),

            // get day from created at
            DB::raw('DATE(created_at) as day')
        
        //group by "day"   
        ])->groupBy('day')

            //get data 30 days with carbon
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();

        if(count($post_views)) {
            foreach ($post_views as $result) {
                $count[]    =(int) $result->count;
                $day[]      =$result->day;
            }
        }else {
            $count[]   ="";
            $day  []   ="";
        }
        
        //return response json
        return response()->json([
            'succes'    => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'categories' => $categories,
                'posts'       => $posts,
                'sliders'     => $sliders,
                'users'       => $users,
                'post_views'  => [
                    'count'   => $count,
                    'days'    => $day
                ]
            ]
        ]);
    }
}