<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Food;
use App\Models\FoodType;


use Encore\Admin\Layout\Content;


class FoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Foods';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Food());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
         $grid->column('FoodType.title', __('Category'));
        $grid->column('price', __('Price'));
        //$grid->column('location', __('Location'));
        $grid->column('stars', __('Stars'));
        $grid->column('img', __('Thumbnail Photo'))->image('',60,60);
        $grid->column('description', __('Description'))->style('max-width:200px;word-break:break-all;')->display(function ($val){
            return substr($val,0,30);
        });
        //$grid->column('total_people', __('People'));
       // $grid->column('selected_people', __('Selected'));
       $grid->column('count', __('report_Count'))->default(0     );
        $grid->column('created_at', __('Created_at'));
        $grid->column('updated_at', __('Updated_at'));

        $grid->column('admin_status', __('Status'))->display(function () {
            // Get the current row's key
            $id = $this->id; // or $this->getKey() depending on your model
        
            // Determine the current status and button text
            $status      = $this->admin_status; // Assuming you have a status attribute in your model
            $buttonText = $status == 1 ? 'clicker pour Blocker ' : 'clicker pour deblockee';
            $buttonColor = $status == 1 ? 'btn-danger' : 'btn-success'; // Red for Block, Green for Unblock
        
            // Generate the URL for the button
            $url = route('updateFoodStatus', ['id' => $id]);
        
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
        $show = new Show(Food::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Food());
        $form->text('name', __('Name'));
          $form->select('type_id', __('Type_id'))->options((new FoodType())::selectOptions());
        $form->number('price', __('Price'));
        $form->text('location', __('Location'));
        $form->number('stars', __('Stars'));
        $form->number('people', __('People'));
        $form->number('selected_people', __('Selected'));
        $form->image('img', __('Thumbnail'))->uniqueName();
        $form->UEditor('description','Description');



        return $form;
    }
}
