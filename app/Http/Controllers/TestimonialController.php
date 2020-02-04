<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Testimonial;

class TestimonialController extends Controller
{
    public function index()
    {
    	$testimonials = Testimonial::all();
    	return view('dashboard.testimonials.index')->withTestimonials($testimonials);
    }

    public function create()
    {
    	return view('dashboard.testimonials.create');
    }

    public function store(Request $request)
    {
    	$request->validate(['testimonial_text'=>'required']);
    	$testimonial = Testimonial::create($request->all());
    	if($testimonial)
    	{
    		$this->success('Testimonial added successfully');
    	}
    	else
    	{
    		$this->error();
    	}
    	return redirect()->back();
    }

    public function edit(Testimonial $testimonial)
    {
    	return view('dashboard.testimonials.edit')->withTestimonial($testimonial);
    }

    public function update(Testimonial $testimonial,Request $request)
    {
    	if($testimonial->update($request->all()))
    	{
    		$this->success('Testimonial Updated Successfully');
    	}
    	else
    	{
    		$this->error();
    	}
    	return redirect()->route('dashboard.testimonials.index');
    }

    public function destroy(Testimonial $testimonial)
    {
    	if($testimonial->delete())
    	{
    		$this->success('Testimonial Deleted Successfully');
    	}
    	else
    	{
    		$this->error();
    	}
    	return redirect()->back();
    }
}
