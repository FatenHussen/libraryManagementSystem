<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;


use App\Services\RatingService;
use App\Http\Requests\RatingRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RatingResource;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
        $this->middleware('auth:api');  // تأكد من أن المستخدم مسجل الدخول
    }

       // دالة لجلب التقييمات (جميع التقييمات أو تقييمات كتاب معين)
       public function index(Request $request)
       {
           try {
               // التحقق مما إذا كان book_id قد تم تمريره في الطلب
               if ($request->has('book_id')) {
                   // إذا تم تمرير book_id، جلب التقييمات لهذا الكتاب
                   $bookId = $request->input('book_id');
                   $ratings = $this->ratingService->getBookRatings($bookId);
               } else {
                   // إذا لم يتم تمرير book_id، جلب جميع التقييمات
                   $ratings = Rating::all();
               }
   
               // إرجاع قائمة التقييمات
               return RatingResource::collection($ratings);
           } catch (\Exception $e) {
               return response()->json(['message' => 'Failed to retrieve ratings.'], Response::HTTP_INTERNAL_SERVER_ERROR);
           }
       
   }
   
    public function store(RatingRequest $request, Book $book)
    {
        try {
            // دمج البيانات مع معرف المستخدم ومعرف الكتاب من JSON المرسل في الطلب
            $data = array_merge($request->validated(), [
                'user_id' => Auth::id(),
                'book_id' => $request->input('book_id'),  // أخذ book_id من Body JSON
            ]);
    
            // إنشاء التقييم
            $rating = $this->ratingService->createRating($data);
    
            return new RatingResource($rating);
        } catch (\Exception $e) {
            \Log::error('Failed to create rating: ' . $e->getMessage());  // تسجيل الخطأ في logs
            return response()->json(['message' => 'Failed to create rating.', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // عرض تقييم معين
    public function show($id)
    {
        try {
            $rating = Rating::findOrFail($id);
            return new RatingResource($rating);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Rating not found.'], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(RatingRequest $request, Book $book, $id)
    {
        try {
            // البحث عن التقييم المراد تحديثه
            $rating = Rating::findOrFail($id);  // هذا السطر سيتحقق من وجود التقييم
            
            // تحقق من الصلاحيات إذا كان المستخدم هو صاحب التقييم
            if (Auth::id() !== $rating->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            
            // تحديث التقييم
            $rating->update($request->validated());
            
            return new RatingResource($rating);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Rating not found.'], 404);
        } catch (\Exception $e) {
            \Log::error('Error updating rating: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update rating.', 'error' => $e->getMessage()], 500);
        }
    }
    

    // حذف تقييم
    public function destroy($id)
    {
        try {
            // جلب التقييم باستخدام ID
            $rating = Rating::findOrFail($id);
    
            // تحقق من أن المستخدم الحالي هو من قام بإنشاء التقييم
            if (Auth::id() !== $rating->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
    
            // حذف التقييم
            $rating->delete();
    
            return response()->json(['message' => 'Rating deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Rating not found.'], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to delete rating: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete rating.', 'error' => $e->getMessage()], 500);
        }
    }
}
