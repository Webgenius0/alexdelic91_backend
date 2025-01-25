<?php

namespace App\Http\Controllers\web\Backend;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
       
        if ($request->ajax()) {
            $data = Subcategory::latest()->get();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('subcategory_name', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', function ($data) {
                    return $data->category->category_name;
                })
               ->addColumn('icon', function ($data) {
                   return '<img src="' . asset($data->icon) . '" width="50px" height="50px" alt="">';
               })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return ' <a href="' . route('admin.subcategories.edit', ['id' => $data->id]) . '" type="button" class="btn btn-success text-white btn-sm" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a> '
                        . ' <a href="javascript:void(0)" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white btn-sm" title="Delete">
                              <i class="bi bi-trash"></i>
                              </a>';
                })
                ->rawColumns(['icon', 'status', 'action','category_name'])
                ->make();
        }
        return view('backend.layouts.subcategory.index');
    }

    public function create() {
        $categories = Category::all();
        return view('backend.layouts.subcategory.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'category_id' => 'required',
            'subcategory_name' => 'required',
            'icon' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($request->hasFile('icon')) {
            $image                        = $request->file('icon');
            $imageName                    = uploadImage($image, 'category');
        }
        $subcategory = new Subcategory();
        $subcategory->category_id = $request->category_id;
        $subcategory->subcategory_name = $request->subcategory_name;
        $subcategory->icon = $imageName;
        $subcategory->save();
        return redirect()->route('admin.subcategories.index')->with('t-success', 'Subcategory created successfully');
    }

    public function edit($id) {
        $subcategory = Subcategory::find($id);
        $categories = Category::all();
        return view('backend.layouts.subcategory.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'category_id' => 'required',
            'subcategory_name' => 'required',
            'icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $subcategory = Subcategory::find($id);
        $subcategory->category_id = $request->category_id;
        $subcategory->subcategory_name = $request->subcategory_name;
        if($request->hasFile('icon')) {
            if($subcategory->icon){
                $previousImagePath = public_path($subcategory->icon);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }
            $image                        = $request->file('icon');
            $imageName                    = uploadImage($image, 'category');
            $subcategory->icon = $imageName;
        }else{
            $subcategory->icon = $subcategory->icon;
        }
        $subcategory->save();
        return redirect()->route('admin.subcategories.index')->with('t-success', 'Subcategory updated successfully');
    }

    public function destroy($id) {
        $data = Subcategory::find($id);
        if($data->icon){
            $previousImagePath = public_path($data->icon);
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $data->delete();
        return response()->json([
           'success' => true,
           'message' => 'Category deleted successfully.',
        ]);
    }

    public function status(int $id) 
    {
        $data = Subcategory::findOrFail($id);
        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }
}
