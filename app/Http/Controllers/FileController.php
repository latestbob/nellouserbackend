<?php

namespace App\Http\Controllers;

use App\Traits\FileUpload;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use FileUpload;

    public function fileUpload(Request $request)
    {
        $file = $this->uploadFile($request, 'file');
        return ['url' => $file];
    }
}
