<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Models\Shoe;

class ApiShoeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Shoe::latest()->paginate(9);
        $products = Shoe::withFilters(
            request()->input('prices', []),
            request()->input('categories', []),
            
        )->latest()->paginate(9);
        return response()->json($products);
    }

    public function showall()
    {
        $user = Shoe::with('category')->get();
        return response()->json($user);
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
        $filename = '';
        if ($request->hasFile('picture')) {
            $avatar = $request->file('picture');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $avatar->move($destinationPath, $filename);
            //$this->save();
        }
        //$product = new Shoe();
        $product = new Shoe([
            'title' => $request->get('title'),
            'content' => $request->get('content'),  
            'picture' => $filename,
            'price' => 12,  
        ]);

        if($request->input("category") != null){
            $product->category_id = $request->category;
        }
        $product->save();
        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Shoe::find($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $product = Shoe::find($id);
        $filename = '';
        if ($request->hasFile('picture')) {
            $avatar = $request->file('picture');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $avatar->move($destinationPath, $filename);
            $product->picture = $filename;
        }

        if($request->input("category") != null){
            $product->category_id = $request->category;
        }

        $product->content = $request->content;
        $product->title = $request->title;
        $product->save();

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Shoe::destroy($id);
        if ($res) {
            return response()->json([
                'status' => 1,
                'msg' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'fail'
            ]);
        }
        return response()->json("success");
    }
}
