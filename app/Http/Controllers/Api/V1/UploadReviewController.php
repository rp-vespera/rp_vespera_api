<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\UploadReview\Services\UploadReviewService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Domain\UploadReview\Models\UploadReview;
use Carbon\Carbon;

class UploadReviewController extends Controller
{
    public function __construct(private UploadReviewService $service) {}

    public function submit(Request $request)
    {
        $request->validate([
            'document_no' => 'required|string',
            'reviewer_name' => 'required|string',
            'q1' => 'required|string',
            'q2' => 'required|string',
            'q3' => 'required|string',
            'q4' => 'required|string',
            'q5' => 'required|string',
            'q6' => 'required|string',
            'fb_username' => 'nullable|string',
            'google_username' => 'nullable|string',
            'fb_screenshot' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'google_screenshot' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Check duplicate reviewer for the same document
        if (UploadReview::where('document_no', $request->document_no)
            ->where('reviewer_name', $request->reviewer_name)
            ->exists()) {
            return response()->json([
                'message' => 'This reviewer has already submitted a review for this document.'
            ], 422);
        }

        // Check fb_username uniqueness per document
        if ($request->fb_username && UploadReview::where('document_no', $request->document_no)
            ->where('fb_username', $request->fb_username)
            ->exists()) {
            return response()->json([
                'message' => 'This Facebook username has already been used for this document.'
            ], 422);
        }

        // Check google_username uniqueness per document
        if ($request->google_username && UploadReview::where('document_no', $request->document_no)
            ->where('google_username', $request->google_username)
            ->exists()) {
            return response()->json([
                'message' => 'This Google username has already been used for this document.'
            ], 422);
        }

        // Handle file uploads and save paths
        $fbPath = $request->file('fb_screenshot') ? $request->file('fb_screenshot')->store('reviews') : null;
        $googlePath = $request->file('google_screenshot') ? $request->file('google_screenshot')->store('reviews') : null;

        // Save review
        UploadReview::create([
            'document_no' => $request->document_no,
            'reviewer_name' => $request->reviewer_name,
            'q1' => $request->q1,
            'q2' => $request->q2,
            'q3' => $request->q3,
            'q4' => $request->q4,
            'q5' => $request->q5,
            'q6' => $request->q6,
            'others' => $request->others,
            'fb_username' => $request->fb_username,
            'google_username' => $request->google_username,
            'fb_screenshot' => $fbPath,
            'google_screenshot' => $googlePath,
            'submitted_at' => now(),
            'is_valid' => true,
        ]);

        return response()->json(['message' => 'Review submitted successfully.']);
    }




    public function getInterments($documentno)
    {
        $query = "SELECT
            bpar.`name1`,
            inter.`documentno`,
            inter.`date_interment`
        FROM mp_t_interment_order inter
        JOIN mp_l_ownership ship USING (mp_l_ownership_id)
        JOIN mp_l_preownership preown USING (mp_l_preownership_id)
        JOIN mp_i_owner owner
            ON preown.`mp_i_owner_id` = owner.`mp_i_owner_id`
        JOIN bpar_i_person bpar
            ON owner.`bpar_i_person_id` = bpar.`bpar_i_person_id`
        WHERE inter.`documentno` = :documentno
          AND inter.`documentno` NOT LIKE '%-CA'
          AND inter.`documentno` NOT LIKE '%DR'";

        $interments = DB::connection('mysql_secondary')
            ->select($query, ['documentno' => $documentno]);

        if (empty($interments)) {
            return response()->json(['message' => 'No records found.'], 404);
        }

        $intermentDate = $interments[0]->date_interment ?? null;

        if (!$intermentDate) {
            return response()->json(['message' => 'Invalid record.'], 500);
        }

        // Expiration: 10 years after interment date
        $expiryDate = Carbon::parse($intermentDate)->addYears(10);
        $now = Carbon::now();

        if ($now->gt($expiryDate)) {
            return response()->json(['message' => 'Link expired.'], 403);
        }

        return response()->json($interments);
    }

}
