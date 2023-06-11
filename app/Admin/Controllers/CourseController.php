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

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', __('Id'));
        $grid->column('user_token', __('Teacher'))->display(
            function($token) {
                // for further processing data you can create
                // any method iside it or do operation
                return User::where('token', '=', $token)
                ->value('name');
            }
        );
        $grid->column('course_name', __('Course name'));
        $grid->column('thumbnail', __('Thumbnail'))->image('', 50,50);
        $grid->column('description', __('Description'));
        $grid->column('type_id', __('Type id'));
        $grid->column('price', __('Price'));
        $grid->column('lesson_num', __('Lesson num'));
        $grid->column('video_lenght', __('Video lenght'));
        $grid->column('created_at', __('Created at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));
        // the first argument is the database field
        $show->field('id', __('Id'));
        $show->field('user_token', __('Teacher'));
        $show->field('course_name', __('Course name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('video', __('Video'));
        $show->field('description', __('Description'));
        $show->field('type_id', __('Type id'));
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson num'));
        $show->field('video_lenght', __('Video lenght'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

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
