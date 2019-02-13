<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{
    use ErrorCodesTrait;
}