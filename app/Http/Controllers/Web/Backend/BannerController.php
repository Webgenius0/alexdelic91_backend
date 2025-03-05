<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Banner::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image_url', function ($data) {
                    return '<img src="' . asset($data->image_url) . '" width="120px" alt="">';
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
                    return ' <a href="' . route('admin.banner.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-success btn-sm" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a> '
                        . ' <a href="javascript:void(0)" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="text-white btn btn-danger btn-sm" title="Delete">
                              <i class="bi bi-trash"></i>
                              </a>';
                })
                ->rawColumns(['image_url', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.banner.index');
    }

    public function create()
    {
        return view('backend.layouts.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_url' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            $image                        = $request->file('image_url');
            $imageName                    = uploadImage($image, 'Banner');
        }

        $banner = new Banner();
        $banner->image_url = $imageName;
        $banner->save();

        return redirect()->route('admin.banner.index')->with('t-success', 'Banner Created Successfully');
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        return view('backend.layouts.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image_url' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $oldBanner = Banner::find($id);

        if ($request->hasFile('image_url')) {

            if ($oldBanner->image_url) {
                $previousImagePath = public_path($oldBanner->image_url);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }

            $image                        = $request->file('image_url');
            $imageName                    = uploadImage($image, 'Banner');
        } else {
            $imageName = $oldBanner->image_url;
        }

        $oldBanner->image_url = $imageName;

        $oldBanner->save();
        return redirect()->route('admin.banner.index')->with('t-success', 'Banner Updated Successfully');
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);

        if ($banner->image_url) {
            $previousImagePath = public_path($banner->image_url);
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully.',
        ]);
    }

    public function status(int $id)
    {
        $data = Banner::findOrFail($id);
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
