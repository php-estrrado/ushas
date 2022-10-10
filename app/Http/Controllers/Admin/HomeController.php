<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      //  return view('home');
        return view('admin.admin');
    }
    
    
    public function dropdownData(Request $request) { 
        $post                   =   (object)$request->post();
        $data                   =   getDropdownValues($post->table,$post->field,$post->value,$post->label);
        $label                  =   $post->label;
        $options                =   '<option value="">'.$post->placeholder.'</option>';
        if($data){ foreach($data    as  $row){
            if($post->selected == $row->id){$selected = 'selected="selected"'; }else{ $selected = ''; }
            $options            .=  '<option value="'.$row->id.'" '.$selected.'>'.$row->$label.'</option>';
        } } return $options;
    }
}
