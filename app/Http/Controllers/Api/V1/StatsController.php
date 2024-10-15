<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function index()
    {

        $stats = Cache::remember('stats',now()->addHour(), function () {
            return [
                'total_users' => User::count(),
                'total_posts' => Post::count(),
                'users_with_zero_posts' => User::doesntHave('posts')->count(),
            ];
        });

        return $this->successResponse(data: $stats);
    }
}
