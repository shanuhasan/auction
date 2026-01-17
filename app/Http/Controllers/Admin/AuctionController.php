<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $auctions = Auction::where('is_deleted', '!=', '1')
                    ->orderBy('id', 'DESC');

        if (!empty($request->get('name'))) {
            $auctions = $auctions->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('status'))) {
            $auctions = $auctions->where('status', 'like', '%' . $request->get('status') . '%');
        }

        $auctions = $auctions->paginate(20);

        return view('admin.auction.index', [
            'auctions' => $auctions
        ]);
    }

    public function create()
    {

        return view('admin.auction.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'season' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $model = new Auction();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->season = $request->season;
            $model->status = $request->status;

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/auction/' . $newImageName;
                File::copy($sPath, $dPath);
                $model->logo = $newImageName;
                $model->save();
            }
            $model->save();

            session()->flash('success', 'Auction added successfully.');
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($guid, Request $request)
    {

        $auction = Auction::findByGuid($guid);
        if (empty($auction)) {
            return redirect()->route('admin.auction.index');
        }

        return view('admin.auction.edit', compact('auction'));
    }

    public function update($guid, Request $request)
    {
        $model = Auction::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Auction not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Auction not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'season' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $model->name = $request->name;
            $model->season = $request->season;
            $model->status = $request->status;
            $model->save();

            $oldImage = $model->logo;

            //save image
            if (!empty($request->image_id)) {
                $media = media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/auction/' . $newImageName;
                File::copy($sPath, $dPath);
                $model->logo = $newImageName;
                $model->save();

                File::delete(public_path() . '/uploads/auction/' . $oldImage);
            }

            $request->session()->flash('success', 'Auction updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Auction updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($guid, Request $request)
    {
        $model = Auction::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Auction not found.');
            return response()->json([
                'status' => true,
                'message' => 'Auction not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success', 'Auction deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Auction deleted successfully.'
        ]);
    }

    public function deletedAuction(Request $request)
    {
        $users = Auction::where('is_deleted', '=', '1')->orderBy('id', 'DESC');

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->paginate(10);

        return view('admin.auction.delete', [
            'users' => $users
        ]);
    }

    public function restore($guid, Request $request)
    {
        $model = Auction::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Auction not found.');
            return response()->json([
                'status' => true,
                'message' => 'Auction not found.'
            ]);
        }

        $model->is_deleted = 0;
        $model->save();

        $request->session()->flash('success', 'Auction Restore successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Auction Restore successfully.'
        ]);
    }
}
