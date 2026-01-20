<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $players = Player::where('is_deleted', '!=', '1')
                    ->orderBy('id', 'DESC');

        if (!empty($request->get('name'))) {
            $players = $players->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $players = $players->paginate(20);

        return view('admin.player.index', [
            'players' => $players
        ]);
    }

    public function create()
    {
        return view('admin.player.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'role' => 'required',
            'base_price' => 'required',
            'jersey_name' => 'required',
            'jersey_number' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $model = new Player();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->role = $request->role;
            $model->base_price = $request->base_price;
            $model->jersey_name = $request->jersey_name;
            $model->jersey_number = $request->jersey_number;
            $model->status = $request->status;

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/player/' . $newImageName;
                File::copy($sPath, $dPath);
                $model->image = $newImageName;
                $model->save();
            }
            $model->save();

            session()->flash('success', 'Player added successfully.');
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

        $player = Player::findByGuid($guid);
        if (empty($player)) {
            return redirect()->route('admin.player.index');
        }

        return view('admin.player.edit', compact('player'));
    }

    public function update($guid, Request $request)
    {
        $model = Player::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Player not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Player not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'role' => 'required',
            'base_price' => 'required',
            'jersey_name' => 'required',
            'jersey_number' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $model->name = $request->name;
            $model->role = $request->role;
            $model->base_price = $request->base_price;
            $model->jersey_name = $request->jersey_name;
            $model->jersey_number = $request->jersey_number;
            $model->status = $request->status;
            $model->save();

            $oldImage = $model->image;

            //save image
            if (!empty($request->image_id)) {
                $media = media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/player/' . $newImageName;
                File::copy($sPath, $dPath);
                $model->image = $newImageName;
                $model->save();

                File::delete(public_path() . '/uploads/player/' . $oldImage);
            }

            $request->session()->flash('success', 'Player updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Player updated successfully.'
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
        $model = Player::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Player not found.');
            return response()->json([
                'status' => true,
                'message' => 'Player not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success', 'Player deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Player deleted successfully.'
        ]);
    }

    public function deletedAuction(Request $request)
    {
        $users = Player::where('is_deleted', '=', '1')->orderBy('id', 'DESC');

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->paginate(10);

        return view('admin.player.delete', [
            'users' => $users
        ]);
    }

    public function restore($guid, Request $request)
    {
        $model = Player::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Player not found.');
            return response()->json([
                'status' => true,
                'message' => 'Player not found.'
            ]);
        }

        $model->is_deleted = 0;
        $model->save();

        $request->session()->flash('success', 'Player Restore successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Player Restore successfully.'
        ]);
    }
}
