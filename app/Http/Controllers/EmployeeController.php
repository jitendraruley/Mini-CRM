<?php

namespace App\Http\Controllers;

use App\Companies;
use App\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
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
        $employees = Employees::latest()->paginate(10);

        return view('employees.index',compact('employees'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Companies::all();
        return view('employees.create', compact('companies'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required',
            'email' => 'required',
            'phone' => 'required|digits:10',
        ]);

        $employee = new Employees();
        $employee->first_name = $request->input('first_name');
        $employee->last_name = $request->input('last_name');
        $employee->company_id = $request->input('company_id');
        $employee->email = $request->input('email');
        $employee->phone = $request->input('phone');

        $employee->save();

        return redirect()->route('employees.index')
            ->with('success','Employee created successfully.');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employees = Employees::find($id);
        return view('employees.show',compact('employees'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employees $employee)
    {
        $companies = Companies::all();
        return view('employees.edit',compact('employee', 'companies'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required',
            'email' => 'required',
            'phone' => 'required|digits:10',
        ]);


        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $company_id = $request->input('company_id');
        $email = $request->input('email');
        $phone = $request->input('phone');


        DB::update('update employees set first_name = ?,last_name=?,company_id=?,email=?,phone=? where id = ?',[$first_name,$last_name,$company_id,$email,$phone,$id]);

        return redirect()->route('employees.index')
            ->with('success','Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employees $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success','Employee deleted successfully');
    }
}
