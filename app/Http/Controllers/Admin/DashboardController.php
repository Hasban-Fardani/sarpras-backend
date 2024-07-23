<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\RequestItem;
use App\Models\SubmissionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getCounts()
    {
        return response()->json([
            'items' => Item::count(),
            'users' => User::count(),
            'requests' => RequestItem::count(),
            'submissions' => SubmissionItem::count(), 
        ]);
    }

    public function getStatsRequest() 
    {
        $data = RequestItem::select(
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', date('Y'))  // Optional: limit to current year
            ->groupBy('month')
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();
        
        $labels = $data->pluck('month')->toArray();
        $values = $data->pluck('count')->toArray();
        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function getStatsItem()
    {
        // get all categories and count their items
        $data = Category::withCount('items')->get();

        $labels = $data->pluck('name')->toArray();
        $values = $data->pluck('items_count')->toArray();
        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
