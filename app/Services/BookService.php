<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function getAllBooks()
    {
        return Book::all();
    }
        

    public function filterBooks($author = null, $categoryId = null, $isAvailable = null)
    {
        // بناء استعلام الكتب
        $query = Book::query();

        // فلترة حسب المؤلف إذا تم تحديده
        if ($author) {
            $query->where('author', 'LIKE', "%{$author}%");
        }

        // فلترة حسب التصنيف إذا تم تحديده
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // فلترة حسب حالة توفر الكتاب إذا تم تحديدها
        if (!is_null($isAvailable)) {
            $query->where('is_available', $isAvailable);
        }

        // إرجاع النتائج
        return $query->get();
    }
    public function createBook(array $data): Book
    {
        return Book::create($data);
    }

    public function updateBook(Book $book, array $data)
    {
        $book->update($data);
        return $book;
    }

    public function deleteBook(Book $book)
    {
        return $book->delete();
    }

    public function getBookById($id)
    {
        return Book::findOrFail($id);
    }
}
