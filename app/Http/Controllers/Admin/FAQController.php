<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\AddRequest;
use App\Http\Requests\Admin\Faq\UpdateRequest;
use App\Repository\Interfaces\FAQRepositoryInterface;

class FAQController extends Controller
{
    private $faq;

    public function __construct(FAQRepositoryInterface $faq)
    {
        $this->faq = $faq;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.faq.index', [ 'faqs' => $this->faq->all() ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.faq.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddRequest $request)
    {
        if ($this->faq->store($request->validated())) {
            return redirect()->route('faq.index')->with('success', 'FAQ created successfully.');
        }

        return redirect()->route('faq.index')->with('error', 'Something went wrong happen!');
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
        return view('admin.faq.edit', [ 'faq' => $this->faq->find($id) ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        if ($this->faq->update($request->validated(), $id)) {
            return redirect()->route('faq.index')->with('success', 'FAQ updated successfully.');
        }

        return redirect()->route('faq.index')->with('error', 'Something went wrong happen!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->faq->delete($id)) {
            return response()->json([ 'message' => 'FAQ delete successfully.' ], 200);
        }

        return response()->json([ 'message' => 'Something went wrong happen!' ], 500);
    }
}
