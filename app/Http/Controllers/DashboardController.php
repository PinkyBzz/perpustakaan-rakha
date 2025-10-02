<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->role === User::ROLE_ADMIN) {
            $stats = [
                'total_books' => Book::count(),
                'total_users' => User::count(),
                'pending_requests' => BorrowRequest::where('status', BorrowRequest::STATUS_PENDING)->count(),
            ];

            $activityChart = BorrowRequest::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $recentRequests = BorrowRequest::with(['book', 'user'])
                ->latest()
                ->limit(8)
                ->get();

            $recentUsers = User::latest()->limit(10)->get();

            return view('admin.dashboard', compact('stats', 'activityChart', 'recentRequests', 'recentUsers'));
        }

        if ($user->role === User::ROLE_PEGAWAI) {
            $stats = [
                'total_books' => Book::count(),
                'pending_requests' => BorrowRequest::where('status', BorrowRequest::STATUS_PENDING)->count(),
                'return_requested' => BorrowRequest::where('status', BorrowRequest::STATUS_RETURN_REQUESTED)->count(),
            ];

            $recentRequests = BorrowRequest::with(['book', 'user'])
                ->latest()
                ->limit(8)
                ->get();

            return view('pegawai.dashboard', compact('stats', 'recentRequests'));
        }

        $availableBooks = Book::withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        $activeBorrows = BorrowRequest::with('book')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.dashboard', compact('availableBooks', 'activeBorrows'));
    }
}
