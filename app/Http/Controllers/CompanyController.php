<?php

namespace App\Http\Controllers;

use App\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Companies::latest()->paginate(10);

        return view('companies.index',compact('companies'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'website' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $company = new Companies();
        $company->name = $request->input('name');
        $company->email = $request->input('email');
        $company->website = $request->input('website');
        //$company->logo = $request->input('name');
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $newfile = time().'.'.$extention;
            $request->image->storeAs('', $newfile, 'public');
        }
        $company->logo = $newfile;

        $company->save();
//        Companies::create($request->all());

        return redirect()->route('companies.index')
            ->with('success','Company created successfully.');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companies = Companies::find($id);
       return view('companies.show',compact('companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Companies $company)
    {
        return view('companies.edit',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {//dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'website' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $name = $request->input('name');
        $email = $request->input('email');
        $website = $request->input('website');

        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $newfile = time().'.'.$extention;
            $request->image->storeAs('', $newfile, 'public');
        }

        $request['image'] = $newfile;
        $logo = $request->input('image');
        DB::update('update companies set name = ?,email=?,logo=?,website=? where id = ?',[$name,$email,$logo,$website,$id]);

        return redirect()->route('companies.index')
            ->with('success','Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Companies $company)
    {
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success','Company deleted successfully');
    }


}
