<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\Exception;
use Yajra\DataTables\DataTables;
use PDF;
use Excel;
use App\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('welcome');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['photo'] = null;

        if ($request->hasFile('photo')){
            $input['photo'] = '/upload/photo/'.str_slug($input['name'], '-').'.'.$request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('/upload/photo/'), $input['photo']);
        }

        Contact::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Contact Created'
        ]);
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
        $contact = Contact::findOrFail($id);
        return $contact;
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
        $input = $request->all();
        $contact = Contact::findOrFail($id);

        $input['photo'] = $contact->photo;

        if ($request->hasFile('photo')){
            if (!$contact->photo == NULL){
                unlink(public_path($contact->photo));
            }
            $input['photo'] = '/upload/photo/'.str_slug($input['name'], '-').'.'.$request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('/upload/photo/'), $input['photo']);
        }

        $contact->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Contact Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        if (!$contact->photo == NULL){
            unlink(public_path($contact->photo));
        }

        Contact::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Contact Deleted'
        ]);
    }

    public function apiContact()
    {
        $contact = Contact::all();
        $nomor = 2;
        return Datatables::of($contact)
            ->addColumn('nomor', function(){
                    global $nomor;

                    return ++$nomor;
             
            })
            ->addColumn('show_photo', function($contact){
                if ($contact->photo == null){
                    return 'No Image';
                }
                return '<img class="rounded-square" width="50" height="50" src="'. url($contact->photo) .'" alt="">';
            })
            ->addColumn('action', function($contact){
                return '<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
                       '<a onclick="editForm('. $contact->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                       '<a onclick="deleteData('. $contact->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['nomor','show_photo', 'action'])->make(true);
    }

    public function exportPDF(){
        $contacts =Contact::limit(100)->get();
        $pdf=PDF::loadView('pdf',compact('contacts'));
        $pdf->setPaper('a4','potrait');
        return $pdf->stream();
    }

    public function exportExcel(){
        $contact =Contact::select('name','email')->get();

        return Excel::create('data-contact',function($excel)use ($contact){
            $excel->sheet('mysheet',function($sheet) use ($contact){
                $sheet->fromArray($contact);
            } );
        })->download('xls');
    }

    public function importExcel(Request $request){
       if ($request->hasFile('file')) {
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path, function($reader){})->get();
        if (!empty($data) && $data->count()) {
            foreach ($data as $key => $value) {
                $contact = new Contact;
                $contact->name = $value->name;
                $contact->email = $value->email;
                $contact->save();
            }
        }
       }
               return response()->json([
            'success' => true,
            'message' => 'Contact Deleted'
        ]);
    }
}
?>
