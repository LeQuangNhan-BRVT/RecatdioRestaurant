<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class HomeController extends Controller
{
    public function index()
    {
        // Tính tổng thu nhập
        $totalIncome = \App\Models\Booking::where('status', 'completed')->sum('total_amount');

        // Lấy dữ liệu cho 7 ngày gần nhất
        $dailyIncomes = \App\Models\Booking::where('status', 'completed')
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chuẩn bị dữ liệu cho biểu đồ
        $labels = [];
        $data = [];
        
        // Tạo mảng cho 7 ngày gần nhất
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $income = $dailyIncomes->firstWhere('date', $date);
            $data[] = $income ? $income->total : 0;
        }

        // Thống kê đơn đặt bàn trong ngày
        $todayBookings = \App\Models\Booking::whereDate('created_at', now())->count();

        // Thống kê số lượng khách hàng
        $totalCustomers = \App\Models\User::where('role', '0')->count();

        return view('admin.dashboard', compact('totalIncome', 'labels', 'data', 'todayBookings', 'totalCustomers'));
        // $admin = Auth::guard('admin')->user();
        // echo 'Hello World '.$admin->name.'<a href = "'.route('admin.logout').'">Đăng xuất</a>';
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function getStatistics(Request $request)
    {
        $type = $request->query('type', 'day');
        $labels = [];
        $data = [];

        switch ($type) {
            case 'month':
                $monthlyIncomes = \App\Models\Booking::where('status', 'completed')
                    ->whereYear('created_at', now()->year)
                    ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = "Tháng $i";
                    $income = $monthlyIncomes->firstWhere('month', $i);
                    $data[] = $income ? $income->total : 0;
                }
                break;

            case 'quarter':
                $quarterlyIncomes = \App\Models\Booking::where('status', 'completed')
                    ->whereYear('created_at', now()->year)
                    ->selectRaw('QUARTER(created_at) as quarter, SUM(total_amount) as total')
                    ->groupBy('quarter')
                    ->orderBy('quarter')
                    ->get();

                for ($i = 1; $i <= 4; $i++) {
                    $labels[] = "Quý $i";
                    $income = $quarterlyIncomes->firstWhere('quarter', $i);
                    $data[] = $income ? $income->total : 0;
                }
                break;

            case 'year':
                $yearlyIncomes = \App\Models\Booking::where('status', 'completed')
                    ->selectRaw('YEAR(created_at) as year, SUM(total_amount) as total')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();

                foreach ($yearlyIncomes as $income) {
                    $labels[] = "Năm " . $income->year;
                    $data[] = $income->total;
                }
                break;

            default: // 'day'
                $dailyIncomes = \App\Models\Booking::where('status', 'completed')
                    ->whereBetween('created_at', [now()->subDays(6), now()])
                    ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i)->format('Y-m-d');
                    $labels[] = now()->subDays($i)->format('d/m');
                    $income = $dailyIncomes->firstWhere('date', $date);
                    $data[] = $income ? $income->total : 0;
                }
                break;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
    public function todayBookingsDetails()
    {
        // Lấy danh sách đơn đặt bàn hôm nay
        $todayBookings = \App\Models\Booking::whereDate('created_at', now())
            ->with(['user']) // Nếu cần thông tin user
            ->latest()
            ->get();

        // Tính tổng thu nhập từ các đơn đặt bàn hôm nay
        $totalIncome = $todayBookings->where('status', 'completed')->sum('total_amount');

        return view('admin.today_booking_details', [
            'todayBookings' => $todayBookings,
            'totalIncome' => $totalIncome
        ]);
    }

}
