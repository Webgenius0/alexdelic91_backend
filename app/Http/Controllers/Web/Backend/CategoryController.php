<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
       
        if ($request->ajax()) {
            $data = Category::latest()->get();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('category_name', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
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
                    return ' <a href="' . route('admin.categories.edit', ['id' => $data->id]) . '" type="button" class="btn btn-success text-white btn-sm" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a> '
                        . ' <a href="javascript:void(0)" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white btn-sm" title="Delete">
                              <i class="bi bi-trash"></i>
                              </a>';
                })
                ->rawColumns(['icon', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.category.index');
    }

    public function create() {
        return view('backend.layouts.category.create');
    }

    public function store(Request $request) {
        $request->validate([
            'category_name' => 'required',
            'icon' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $image                        = $request->file('icon');
            $imageName                    = uploadImage($image, 'category');
           
        }
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->icon = $imageName;
        $category->save();
        return redirect()->route('admin.categories.index')->with('t-success', 'Category Created Successfully');
    }

    public function edit($id) {
        $category = Category::find($id);
        return view('backend.layouts.category.edit', compact('category'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'category_name' => 'required',
            'icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $category = Category::find($id);

        if ($request->hasFile('icon')) {

            if($category->icon){
                $previousImagePath = public_path($category->icon);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }

            $image                        = $request->file('icon');
            $imageName                    = uploadImage($image, 'category');
            $category->icon = $imageName;
        }else{
            $category->icon = $category->icon;
        }
        $category->category_name = $request->category_name;
        $category->save();
        return redirect()->route('admin.categories.index')->with('t-success', 'Category Updated Successfully');
    }

    public function destroy($id) {
        $category = Category::find($id);
        if($category->icon){
            $previousImagePath = public_path($category->icon);
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $category->delete();
        return response()->json([
           'success' => true,
           'message' => 'Category deleted successfully.',
        ]);
    }

    public function status(int $id) 
    {
        $data = Category::findOrFail($id);
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
