<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //returning all course list
    public function courseList()
    {
        try {
            $result = Course::select('course_name', 'thumbnail', 'lesson_num', 'price', 'id')->get();

            return response()->json([
                'code' => 200,
                'msg' => 'My course list is here',
                'data' => $result
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'msg' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
