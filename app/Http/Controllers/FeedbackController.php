<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Applicant: show feedback form
    public function create()
    {
        $myFeedback = Feedback::where('user_id', auth()->id())->first();
        return view('feedback.create', compact('myFeedback'));
    }

    // Applicant: submit feedback
    public function store(Request $request)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        // One feedback per user — update if exists
        Feedback::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    // Admin: view all feedbacks
    public function index()
    {
        $feedbacks = Feedback::with('user')
            ->latest()
            ->get();

        return view('feedback.index', compact('feedbacks'));
    }
}