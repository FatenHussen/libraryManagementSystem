<?php
namespace App\Services;

use Exception;
use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;

class BorrowService
{
    // Get all borrow records (Admin)
    public function getAllBorrows()
    {
        return BorrowRecord::with('user', 'book')->get();
    }

    // Get a specific borrow record by ID (Admin and User)
    public function getBorrowRecordById(int $id)
    {
        return BorrowRecord::with('user', 'book')->findOrFail($id);
    }

    // Create a new borrow record (User)
    public function createBorrowRecord(int $bookId)
    {
        $book = Book::findOrFail($bookId);
        
        // Check if the book is available
        if (!$this->isBookAvailable($book)) {
            throw new Exception('The book is currently not available for borrowing.');
        }

        // Create a new borrow record
        return BorrowRecord::create([
            'book_id' => $bookId,
            'user_id' => Auth::id(),
            'borrowed_at' => now(),
            'due_date' => now()->addDays(14),  // Set due date 14 days from borrow date
        ]);
    }

    // Mark a borrow record as returned (User)
    public function returnBook(int $borrowRecordId)
    {
        $borrowRecord = BorrowRecord::findOrFail($borrowRecordId);

       

        // Mark the record as returned
        $borrowRecord->update(['returned_at' => now()]);

        return $borrowRecord;
    }

    // Admin: Update the borrow request status (Approve or Reject)
         // Admin: Update the borrow request status
  // Update the borrow request status (approve or reject)
    public function updateBorrowStatus(int $borrowRecordId, string $status)
    {
        $borrowRecord = BorrowRecord::findOrFail($borrowRecordId);

        // If approved, check if the book is available
        if ($status === 'approved') {
            if (BorrowRecord::where('book_id', $borrowRecord->book_id)->whereNull('returned_at')->exists()) {
                throw new Exception('The book is no longer available for borrowing.');
            }
        }

        $borrowRecord->update(['status' => $status]);

        return $borrowRecord;
    }

    // Delete the borrow record when rejected
    public function deleteBorrowRecord(BorrowRecord $borrowRecord)
    {
        $borrowRecord->delete();
    }

 

    // Check if the book is available for borrowing
    public function isBookAvailable(Book $book)
    {
        return !BorrowRecord::where('book_id', $book->id)
                            ->whereNull('returned_at')
                            ->exists();
    }


}
