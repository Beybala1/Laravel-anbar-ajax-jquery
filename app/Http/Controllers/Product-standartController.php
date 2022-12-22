<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('product',[
           'brand_list'=>Brand::orderby('brend','asc')->get(),
           'data_list'=>Product::latest()->with('brand')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $request->validate([
            'product'=>'required|min:3',
            'brand_id'=>'required',
            'buy'=>'required',
            'sell'=>'required',
            'count'=>'required',
            'logo' => 'required|mimes:jpeg,png,jpg,gif',
        ]);

        if($request->hasFile('logo')){
            $form['logo'] = $request->file('logo')->store('logos', 'public');
        }

        date_default_timezone_set('Asia/Baku');
        
        Product::create($form);

        return back()->with('message','Məhsul uğurla əlavə edildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('forms.product_edit',[
            'form_data'=>Product::find($id),
            'brand_list'=>Brand::orderby('brend','asc')->get(),
            'data_list'=>Product::latest()->with('brand')->paginate(10),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form = $request->validate([
            'product'=>'required|min:3',
            'brand_id'=>'required',
            'buy'=>'required',
            'sell'=>'required',
            'count'=>'required',
            'logo' => 'mimes:jpeg,png,jpg,gif',
        ]);

        if($request->hasFile('logo')){
            $form['logo'] = $request->file('logo')->store('logos', 'public');
        }

        date_default_timezone_set('Asia/Baku');

        $table = Product::find($id);
        
        $table-> update($form);

        return redirect('/product')->with('message','Məhsul uğurla yeniləndi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return back()->with('message','Məhsul uğurla silindi');
    }
}
