<?php

namespace App\Providers;
use App\Models\Book;
use App\Models\User;
use App\Models\Rating;
use App\Models\BorrowRecord;
use App\Policies\BookPolicy;
use App\Policies\RatingPolicy;
use App\Policies\BorrowRecordPolicy;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */


  protected $policies = [
        Book::class => BookPolicy::class,
        Rating::class => RatingPolicy::class,
        User::class => UserPolicy::class,
        BorrowRecord::class => BorrowRecordPolicy::class,
        Rating::class => RatingPolicy::class,  // تسجيل RatingPolicy

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
