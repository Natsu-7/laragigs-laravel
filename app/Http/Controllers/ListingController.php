<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index()
    {
        return view('listings.index',[
            'lists' => Listing::latest() ->filter(request(['tag', 'search']))->simplePaginate(6)
        ]);

    }
    public function show(Listing $listing)
    {
        return view('listings.show',[
            'listing' => $listing
        ]);
    }



    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $formfields = $request->validate([
            'title'=> 'required',
            'company'=>['required',Rule::unique('listings','company')],
            'location'=> 'required',
            'website'=>'required',
            'email' => ['required', 'email'],
            'tags'=> 'required',
            'description'=>'required'
        ]);

        if($request->hasFile('logo'))
        {
            $formfields['logo'] = $request->file('logo')->store('logos','public');
        }

        $formfields['user_id'] = auth()->id();

        Listing::create($formfields);

        return redirect('/')->with('message','Successfully Posted');
    }

    public function edit(Listing $listing)
    {
        return view('listings.edit',[
            'listing'=>$listing
        ]);
    }
    public function update(Request $request, Listing $listing)
    {
        if($listing->user_id != auth()->id())
        {
            abort(403,'Unauthorized Action');
        }
        $formfields = $request->validate([
            'title'=> 'required',
            'company'=>['required'],
            'location'=> 'required',
            'website'=>'required',
            'email' => ['required', 'email'],
            'tags'=> 'required',
            'description'=>'required'
        ]);

        if($request->hasFile('logo'))
        {
            $formfields['logo'] = $request->file('logo')->store('logos','public');
        }


        $listing->update($formfields);

        return back()->with('message','Updation Successfully');
    }

    public function destroy(Listing $listing)
    {

        if($listing->user_id != auth()->id())
        {
            abort(403,'Unauthorized Action');
        }
        $listing->delete();
        return redirect('/')->with('message','Deleted Successfully');
    }

    public function manage()
    {
        return view('listings.manage',[
            'listings'=> auth()->user()->listings()->get()
        ]);
    }
}
