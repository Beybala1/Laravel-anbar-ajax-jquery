<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('brand',[
            'data_list'=>Brand::paginate(10),
            'data_product'=>Product::get(),
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
            'brend' => ['required','min:2',Rule::unique('brands','brend')],
            'logo' => 'required|mimes:jpeg,png,jpg,gif',
        ]);

        if($request->hasFile('logo')){
            $form['logo'] = $request->file('logo')->store('logos', 'public');
        }

        date_default_timezone_set('Asia/Baku');

        Brand::create($form);

        return back()->with('message','Brend uğurla əlavə edildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('forms.brand_edit', [
            'form_data'=>Brand::find($id),
            'data_list'=>Brand::latest()->paginate(10),
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
            'brend' => 'required|min:2',
            'logo' => 'mimes:jpeg,png,jpg,gif',
        ]);

        if($request->hasFile('logo')){
            $form['logo'] = $request->file('logo')->store('logos', 'public');
        }

        date_default_timezone_set('Asia/Baku');

        $table = Brand::find($id);

        $table->update($form);
        
        return redirect('/')->with('message','Brend uğurla yeniləndi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Product::where('brand_id', '=', $id)->count();

        if($count == 0){
            
            Brand::find($id)->delete();
            return back();
        }
        return back()->with('message_fail','Bu brendi silmək istəyirsinizsə brendə aid məhsullari silin');
    }
}
