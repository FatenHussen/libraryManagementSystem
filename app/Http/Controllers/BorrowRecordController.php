<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\BorrowService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BorrowResource;

use Symfony\Component\HttpFoundation\Response;

class BorrowRecordController extends Controller
{
    protected $borrowService;

    public function __construct(BorrowService $borrowService)
    {
        $this->borrowService = $borrowService;

        // Ensure the user is authenticated
        $this->middleware('auth:api');
    }

    // Admin: Get all borrow records
    public function index()
    {
        try {
            $borrowRecords = $this->borrowService->getAllBorrows();
            return BorrowResource::collection($borrowRecords);
        } catch (Exception $e) {
            Log::error('Error fetching borrow records: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve borrow records.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // User: Create a new borrow request
    public function store(Request $request)
    {
        try {
            $borrowRecord = $this->borrowService->createBorrowRecord($request->book_id);
            return new BorrowResource($borrowRecord);
        } catch (Exception $e) {
            Log::error('Error creating borrow record: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        }
    }

    // User: Return a borrowed book
    public function update(Request $request, $id)
    {
        try {
            $borrowRecord = $this->borrowService->returnBook((int) $id);
            return response()->json(['message' => 'Book returned successfully.'], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error returning book: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Admin: Update the borrow request status (Approve or Reject)
    // public function accept(Request $request, $id)
    // {
    //     try {
    //         $status = $request->input('status');  // "approved" or "rejected"
    //         $borrowRecord = $this->borrowService->updateBorrowStatus((int) $id, $status);

    //         return new BorrowResource($borrowRecord);
    //     } catch (Exception $e) {
    //         Log::error('Error updating borrow status: ' . $e->getMessage());
    //         return response()->json(['message' => 'Failed to update borrow status.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }


    // Admin: Approve or reject a borrow request
    public function approveOrRejectBorrow(Request $request, $id)
    {
        // Ensure the authenticated user has the "admin" role
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access. Admins only can perform this action.'], Response::HTTP_FORBIDDEN);
        }

        try {
            $status = $request->input('status');  // "approved" or "rejected"

            // Validate the status value
            if (!in_array($status, ['approved', 'rejected'])) {
                return response()->json(['message' => 'Invalid status value. Use "approved" or "rejected".'], Response::HTTP_BAD_REQUEST);
            }

            // If the status is "rejected", delete the borrow record
            if ($status === 'rejected') {
                // Get the borrow record by ID
                $borrowRecord = $this->borrowService->getBorrowRecordById((int) $id);

                // Delete the borrow record
                $this->borrowService->deleteBorrowRecord($borrowRecord);

                return response()->json(['message' => 'Borrow request rejected and record deleted.'], Response::HTTP_OK);
            }

            // If the status is "approved", update the record
            $borrowRecord = $this->borrowService->updateBorrowStatus((int) $id, $status);

            return new BorrowResource($borrowRecord);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update borrow status.', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
      

    // Admin/User: Show a specific borrow record
    public function show($id)
    {
        try {
            $borrowRecord = $this->borrowService->getBorrowRecordById((int) $id);
            return new BorrowResource($borrowRecord);
        } catch (Exception $e) {
            Log::error('Error fetching borrow record: ' . $e->getMessage());
            return response()->json(['message' => 'Borrow record not found.'], Response::HTTP_NOT_FOUND);
        }
    }
}
