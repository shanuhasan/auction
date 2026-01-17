<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Models\Media;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::where('is_deleted', '!=', '1')
                    ->orderBy('id', 'DESC');

        if (!empty($request->get('name'))) {
            $teams = $teams->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('auction_id'))) {
            $auction = Auction::findByGuid($request->get('auction_id'));

            $teams = $teams->where('auction_id', '=', $auction->id);
        }

        $teams = $teams->paginate(20);

        return view('admin.team.index', [
            'teams' => $teams
        ]);
    }

    public function create()
    {

        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'auction_id' => 'required|numeric',
            'total_purse' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $model = new Team();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->short_name = $request->short_name;
            $model->auction_id = $request->auction_id;
            $model->total_purse = $request->total_purse;
            $model->status = $request->status;

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/team/' . $newImageName;
                File::copy($sPath, $dPath);
                $model->logo = $newImageName;
                $model->save();
            }
            $model->save();

            session()->flash('success', 'Team added successfully.');
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

        $team = Team::findByGuid($guid);
        if (empty($team)) {
            return redirect()->route('admin.team.index');
        }

        return view('admin.team.edit', compact('team'));
    }

    public function update($guid, Request $request)
    {
        $model = Team::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Team not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Team not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'auction_id' => 'required|numeric',
            'total_purse' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $model->name = $request->name;
            $model->short_name = $request->short_name;
            $model->auction_id = $request->auction_id;
            $model->total_purse = $request->total_purse;
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
                $dPath = public_path() . '/uploads/team/' . $newImageName;
                File::copy($sPath, $dPath);
                $model->logo = $newImageName;
                $model->save();

                File::delete(public_path() . '/uploads/team/' . $oldImage);
            }

            $request->session()->flash('success', 'Team updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Team updated successfully.'
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
        $model = Team::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Team not found.');
            return response()->json([
                'status' => true,
                'message' => 'Team not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success', 'Team deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Team deleted successfully.'
        ]);
    }

    public function deletedAuction(Request $request)
    {
        $users = Team::where('is_deleted', '=', '1')->orderBy('id', 'DESC');

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->paginate(10);

        return view('admin.team.delete', [
            'users' => $users
        ]);
    }

    public function restore($guid, Request $request)
    {
        $model = Team::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Team not found.');
            return response()->json([
                'status' => true,
                'message' => 'Team not found.'
            ]);
        }

        $model->is_deleted = 0;
        $model->save();

        $request->session()->flash('success', 'Team Restore successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Team Restore successfully.'
        ]);
    }
}
