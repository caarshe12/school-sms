<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Attendance;
use App\Models\Activity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'student') {
            return redirect()->route('student.portal');
        }
        // Stats
        $totalStudents = Student::count();
        $staffCount = Staff::count();
        
        // Mocking attendance % for 'today' based on the latest record
        $latest = Attendance::orderBy('date', 'desc')->first();
        $attendanceRate = $latest ? round(($latest->present_count / $latest->total_count) * 100, 1) : 0;
        
        // Mock Revenue
        $monthlyRevenue = 45200;

        // Chart Data (Last 30 days)
        $trend = Attendance::orderBy('date', 'asc')->take(30)->get();
        // Since we created recent dates, we take them reversed from seeding logic but here ordered ASC for chart
        
        // Formatting for Chart.js
        $chartLabels = $trend->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $chartData = $trend->pluck('present_count'); // raw count
        
        // Recent Activities
        $recentActivities = Activity::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalStudents', 
            'staffCount', 
            'attendanceRate', 
            'monthlyRevenue', 
            'chartLabels', 
            'chartData', 
            'recentActivities'
        ));
    }
}
