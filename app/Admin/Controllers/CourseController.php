<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\CourseType;
use App\Models\Course;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class CourseController extends AdminController
{

    protected function grid() {
        $grid = new Grid(new Course());
        return $grid;
    }
    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Category'));
        $show->field('description', __('Description'));
        $show->field('order', __('Order'));
        $show->field('likes', __('Likes'));
        $show->field('dislikes', __('Dislikes'));
        $show->field('created_at', __('Created at'));
        $show->column('updated_at', __('Updated at'));
        return $show;
    }

    protected function form()
    {
        $form = new Form(new Course());
        $form->text('course_name', __('Course Name'));
        //get our categories
        //key-value pair || //* last one is the key
        $result = CourseType::pluck('title', 'id');
        //get result from type_id "SELECT with FK"
        $form->select('type_id', __('Category'))->options($result);
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        //file is used from video and other file format .doc, .pdf
        $form->file('video', __('Video'))->uniqueName();
        $form->text('description', __('Description'));
        //decimal using for float fields of db
        $form->decimal('price', __('Price'));
        $form->number('lesson_num', __('Lessons number'));
        $form->number('video_lenght', __('Video lenght'));
        //for posting who is posting
        $result = User::pluck('name', 'token');
        $form->select('user_token', __('Teacher'))->options($result);
        $form->display('created_at', __('Created at'));
        $form->display('updated_at', __('Updated at'));
        // $form->text('title', __('Title'));
        // $form->textarea('description', __('Description'));
        // $form->number('order', __('Order'));

        return $form;
    }
}
