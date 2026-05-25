<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Http\Requests\StoreLoanApplicationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoanApplicationController extends Controller
{
    use AuthorizesRequests;
    // Applicant: list own applications
    public function index()
    {
        $this->authorize('viewAny', LoanApplication::class);
        $applications = LoanApplication::where('user_id', auth()->id())
            ->latest()->paginate(10);
        return view('applications.index', compact('applications'));
    }

    // Applicant: show form
    public function create()
    {
        $this->authorize('create', LoanApplication::class);
        return view('applications.create');
    }

    // Applicant: submit form
    public function store(StoreLoanApplicationRequest $request)
    {
        $validated = $request->validated();
        $riskData  = $this->getRiskScore($validated);

        LoanApplication::create([
            ...$validated,
            'user_id'    => auth()->id(),
            'risk_score' => $riskData['risk_score'],
            'risk_label' => $riskData['label'],
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application submitted successfully! Risk Score: ' . round($riskData['risk_score'] * 100, 1) . '%');
    }

    // Applicant: view single
    public function show(LoanApplication $loanApplication)
    {
        $this->authorize('view', $loanApplication);
        return view('applications.show', ['application' => $loanApplication]);
    }

    // Applicant: delete pending
    public function destroy(LoanApplication $loanApplication)
    {
        $this->authorize('delete', $loanApplication);
        $loanApplication->delete();
        return redirect()->route('applications.index')
            ->with('success', 'Application withdrawn successfully.');
    }

    // Admin: all applications with filters
    public function adminIndex(Request $request)
    {
        $this->authorize('viewAny', LoanApplication::class);

        $query = LoanApplication::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('risk')) {
            $query->where('risk_label', $request->risk);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->paginate(15)->appends($request->all());
        $stats = [
            'total'    => LoanApplication::count(),
            'pending'  => LoanApplication::where('status', 'pending')->count(),
            'approved' => LoanApplication::where('status', 'approved')->count(),
            'rejected' => LoanApplication::where('status', 'rejected')->count(),
            'high_risk'=> LoanApplication::where('risk_label', 'high')->count(),
        ];

        return view('admin.applications.index', compact('applications', 'stats'));
    }

    // Admin: approve/reject via AJAX
    public function updateStatus(Request $request, LoanApplication $loanApplication)
    {
        $this->authorize('updateStatus', LoanApplication::class);
        $request->validate(['status' => ['required', 'in:approved,rejected']]);

        $loanApplication->update(['status' => $request->status]);

        return response()->json([
            'success'    => true,
            'status'     => $loanApplication->status,
            'badge_class'=> $loanApplication->statusBadge(),
            'message'    => 'Application ' . $loanApplication->status . '.',
        ]);
    }


    // Download PDF
    public function downloadPdf(LoanApplication $loanApplication)
    {
        $this->authorize('view', $loanApplication);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('applications.pdf', [
            'application' => $loanApplication
        ]);

        return $pdf->download('LoanGuard-Application-#' . $loanApplication->id . '.pdf');
    }



    // Internal: Flask ML call with fallback
    private function getRiskScore(array $data): array
    {
        try {
            $response = Http::timeout(5)->post('http://localhost:5000/predict', [
                'age'              => (int) $data['age'],
                'income'           => (float) $data['income'],
                'loan_amount'      => (float) $data['loan_amount'],
                'credit_score'     => (int) $data['credit_score'],
                'employment_years' => (int) $data['employment_years'],
            ]);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {}

        // Rule-based fallback when Flask is offline
        $score = 0.4;
        if ($data['credit_score'] < 580)                                   $score += 0.25;
        if ($data['income'] < 30000)                                       $score += 0.15;
        if ($data['loan_amount'] / max($data['income'], 1) > 5)            $score += 0.15;
        if ($data['employment_years'] < 2)                                 $score += 0.10;
        $score = min($score, 0.99);

        return [
            'risk_score' => round($score, 4),
            'label'      => $score >= 0.5 ? 'high' : 'low',
        ];
    }
}