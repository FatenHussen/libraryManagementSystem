<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService;
use App\Http\Requests\BookRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    protected $bookService;

    // حقن خدمة الكتاب (BookService) في وحدة التحكم
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

  
    // عرض جميع الكتب مع إمكانية الفلترة واستخدام الكاش
    public function index(Request $request)
    {
        // الحصول على قيم الفلاتر من الطلب
        $author = $request->input('author');
        $categoryId = $request->input('category_id'); // استخدام category_id
        $isAvailable = $request->input('is_available');

        // توليد مفتاح الكاش بناءً على معايير البحث
        $cacheKey = 'books_' . md5(json_encode($request->all()));

        // محاولة استرجاع البيانات من الكاش
        $books = Cache::remember($cacheKey, 60 * 60, function () use ($author, $categoryId, $isAvailable) {
            // إذا لم تكن البيانات في الكاش، نقوم بتصفية الكتب باستخدام BookService
            return $this->bookService->filterBooks($author, $categoryId, $isAvailable);
        });

        // إرجاع النتائج باستخدام BookResource
        return BookResource::collection($books);
    }
    // public function index()
    // {
    //     try {
    //         // استدعاء الخدمة لجلب جميع الكتب
    //         $books = $this->bookService->getAllBooks();
    //         return BookResource::collection($books);
    //     } catch (Exception $e) {
    //         // تسجيل الخطأ وإرجاع استجابة بالخطأ
    //         \Log::error('Error fetching books: ' . $e->getMessage());
    //         return response()->json(['message' => 'Failed to fetch books.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
       
    // }

    // عرض كتاب معين
    public function show($id)
    {
        try {
            // جلب الكتاب باستخدام الخدمة
            $book = $this->bookService->getBookById($id);

            if (!$book) {
                return response()->json(['message' => 'Book not found.'], Response::HTTP_NOT_FOUND);
            }

            return new BookResource($book);
        } catch (Exception $e) {
            // تسجيل الخطأ وإرجاع استجابة بالخطأ
            \Log::error('Error fetching book: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch book.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // إنشاء كتاب جديد
    public function store(BookRequest $request)
    {
        try {
            // استدعاء الخدمة لإنشاء كتاب جديد
            $book = $this->bookService->createBook($request->validated());
            return new BookResource($book);
        } catch (Exception $e) {
            // تسجيل الخطأ وإرجاع استجابة بالخطأ
            \Log::error('Error creating book: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create book.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // تحديث بيانات كتاب
    public function update(BookRequest $request, $id)
    {
        try {
            // جلب الكتاب عن طريق الخدمة
            $book = $this->bookService->getBookById($id);
            
            // التحقق من أن الكتاب موجود
            if (!$book) {
                return response()->json(['message' => 'Book not found.'], Response::HTTP_NOT_FOUND);
            }

            // تحديث الكتاب
            $updatedBook = $this->bookService->updateBook($book, $request->validated());
            return new BookResource($updatedBook);
        } catch (Exception $e) {
            // تسجيل الخطأ وإرجاع استجابة بالخطأ
            \Log::error('Error updating book: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update book.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // حذف كتاب
    public function destroy($id)
    {
        try {
            // جلب الكتاب عن طريق الخدمة
            $book = $this->bookService->getBookById($id);
            
            // التحقق من أن الكتاب موجود
            if (!$book) {
                return response()->json(['message' => 'Book not found.'], Response::HTTP_NOT_FOUND);
            }

            // حذف الكتاب
            $this->bookService->deleteBook($book);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            // تسجيل الخطأ وإرجاع استجابة بالخطأ
            \Log::error('Error deleting book: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete book.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAverageRating($bookId)
{
    $book = Book::findOrFail($bookId);
    $averageRating = $book->ratings()->avg('rating') ?: 0;

    return response()->json([
        'book_id' => $book->id,
        'average_rating' => $averageRating
    ]);
}
}
