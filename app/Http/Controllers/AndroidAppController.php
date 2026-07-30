<?php

namespace App\Http\Controllers;

use App\Models\AndroidAppVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AndroidAppController extends Controller
{

    public function check_update(Request $request)
    {
        // Versi saat ini yang sedang dipakai di HP user dikirim oleh plugin
        $currentVersion = $request->input('version', '1.0.0');
        $appVersion = AndroidAppVersion::latest()->first();
        // Versi terbaru yang ada di server Laravel lo
        $latestVersion = $appVersion->version ?? "1.0.0";

        if (version_compare($latestVersion, $currentVersion, '>')) {
            return response()->json([
                'url' => $appVersion->bundle_url ?? route('api.android.fallback-version'), // File zip berisi folder www
                'version' => $latestVersion,
                'status' => 'success'
            ]);
        }

        return response()->json([
            'status' => 'no_update'
        ]);
    }

    public function update_failed(Request $request)
    {
        $file = storage_path("app/private/android/default.zip");
        return response()->download($file, 'default.zip');
    }


    public function bundleAssetUploaded(Request $request)
    {
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        if ($token !== config('services.github.upload_token', '11d57a38973b742c596395fe4d43975fd0c0089a')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($request->hasFile('file')) {
            $version = $request->input('version', 'v1.0.0');
            $file = $request->file('file');

            $filename = "android/bundles/{$version}.zip";
            $file->storeAs('public', $filename);

            DB::beginTransaction();
            try {
                AndroidAppVersion::updateOrCreate([
                    'version' => $version
                ], [
                    'bundle_url' => asset("storage/{$filename}")
                ]);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }

            return response()->json([
                'success' => true,
                'message' => 'Bundle uploaded successfully',
                'url' => asset("storage/{$filename}")
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
