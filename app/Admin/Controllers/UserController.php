<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // $grid->column('id', __('Id'));
        $grid->id("Consomm id");
        $grid->column('f_name', __('Name_Consomm'));
        $grid->column('email', __('Email'));
        // $grid->column('email_verified_at', __('Email verified at'));
        $grid->email_verified_at("isVerified")->display(function($verif){
            return $verif?"Yes":"No" ;
        });
        $grid->column('password', __('Password'));
        $grid->column('remember_token', __('Remember token'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('status', __('Status'))->display(function () {
            // Get the current row's key
            $id = $this->id; // or $this->getKey() depending on your model
        
            // Determine the current status and button text
            $status = $this->status; // Assuming you have a status attribute in your model
            $buttonText = $status == 1 ? 'Block' : 'Unblock';
            $buttonColor = $status == 1 ? 'btn-danger' : 'btn-success'; // Red for Block, Green for Unblock
        
            // Generate the URL for the button
            $url = route('admin.users.change-status', ['id' => $id]);
        
            // Return HTML for the button
            return '<a href="' . $url . '" class="btn btn-sm ' . $buttonColor . '" onclick="return confirm(\'Are you sure?\')">' . $buttonText . '</a>';
        });
        

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('remember_token', __('Remember token'));

        return $form;
    }
}
