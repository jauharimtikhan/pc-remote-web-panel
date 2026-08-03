<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiSyncShortcutResolumeController extends Controller
{
    public function sync(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => "required",
            'shortcuts' => "required|array"
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            Log::debug("SHORTCUT DATA:" . json_encode($request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $device = Device::where('device_id', $request->device_id)->first();

            if (!$device) {
                return response()->json('failed', 404);
            }

            $device->update([
                'shortcuts' => $request->shortcuts
            ]);
            DB::commit();
            return response()->json('ok', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json('failed', 500);
        }
    }
}
