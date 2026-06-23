 <?php

    use App\Http\Controllers\Admin\Cms\HeroImageController;
    use Illuminate\Support\Facades\Route;

    Route::middleware('auth:sanctum')
        ->prefix('admin/cms/hero-image')
        ->name('admin.cms.hero-image.')
        ->controller(HeroImageController::class)
        ->group(function () {
            Route::get('/', 'show');
            Route::post('/', 'store');
            Route::put('/', 'update');
            Route::delete('/', 'destroy');
        });
