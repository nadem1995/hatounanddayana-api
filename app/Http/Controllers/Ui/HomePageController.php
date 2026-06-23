<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Services\HomeService;

class HomePageController extends Controller
{
    

    public function index(HomeService $service)
    {
        return $service->getHomeData();
    }
}
