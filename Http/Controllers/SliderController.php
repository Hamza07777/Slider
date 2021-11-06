<?php

namespace Modules\Slider\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slider\Entities\Slide;


class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (view()->exists('slide.list'))

        {
            $slide=Slide::all();
            session()->flash('alert-type', 'success');
            session()->flash('message', 'Page is Loading .......');
            return view('slide.list')->with('slide',$slide);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (view()->exists('slide.new'))

        {

            return view('slide.new');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'heading' => 'required|string|max:255',
            'subheading' => 'string|max:255',
            'button_text' => 'string|max:255',
            'file' => 'required|mimes:jpeg,png,jpg',
       ]);

       if ($request->hasFile('file')) {
        $extension=$request->file->extension();
        $filename=time()."_.".$extension;
        $request->file->move(public_path('slider'), $filename);
    }
        $slide=Slide::create([
            'heading' => $request['heading'],
            'subheading' => $request['subheading'],
            'button_text' => $request['button_text'],
            'file' => $filename,
        ]);

            if($slide)
            {
                session()->flash('alert-type', 'success');
                session()->flash('message', 'Slide added successfully');
                return redirect()->route('slide.index');
            }
            else{
                session()->flash('alert-type', 'error');
                session()->flash('message', 'Record Not Added.');
                return redirect()->back();
            }
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
        if (view()->exists('slide.new'))

        {

            $slide=Slide::findOrFail($id);
            session()->flash('alert-type', 'success');
            session()->flash('message', 'Page is Loading .......');
            return view('slide.new')->with('slide',$slide);
        }
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
        $slider=Slide::findOrFail($id);
        $validatedData = $request->validate([
            'heading' => 'required|string|max:255',
            'subheading' => 'string',
            'button_text' => 'string|max:255',
            'file' => 'mimes:jpeg,png,jpg',
       ]);
       if ($request->hasFile('file')) {

           if (isset($slider->file) && file_exists(public_path('slider/'.$slider->file))) {
               unlink(public_path('slider/'.$slider->file));
           }
           $extension=$request->file->extension();
           $filename=time()."_.".$extension;
           $request->file->move(public_path('slider'), $filename);
       }
       else{
        $filename=$slider->file;
       }
        $slide=Slide::whereId($id)->update([
            'heading' => $request['heading'],
            'subheading' => $request['subheading'],
            'button_text' => $request['button_text'],
            'file' => $filename,
            ]);

          if($slide)
            {
                    session()->flash('alert-type', 'success');
                    session()->flash('message', 'Slide Updated Successfully.');
                    return redirect()->route('slide.index');
            }
            else{
                session()->flash('alert-type', 'error');
                session()->flash('message', 'Record Not Updated.');
                return redirect()->back();
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slide::findOrFail($id);
        if (isset($slider->file) && file_exists(public_path('slider/'.$slider->file))) {
            unlink(public_path('slider/'.$slider->file));
        }
        $slider->delete();

        session()->flash('alert-type', 'success');
        session()->flash('message', 'Slide deleted successfully');

        return redirect()->route('slide.index');
    }

    public function multiplecourse_quizdelete(Request $request)
	{
		$id = $request->id;
		foreach ($id as $user)
		{
            $slider = Slide::findOrFail($user);
                if (isset($slider->file) && file_exists(public_path('slider/'.$slider->file))) {
                    unlink(public_path('slider/'.$slider->file));
                }
            $slider->delete();
		}
        session()->flash('alert-type', 'success');
        session()->flash('message', 'Slides deleted successfully');

        return redirect()->route('slide.index');
	}

}

