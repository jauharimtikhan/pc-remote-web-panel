<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected static function alert(string $type = 'success', string $msg = '')
    {
        session()->flash('alert', [
            'type' => $type,
            'message' => $msg
        ]);
    }

    protected static function backWithAlert(string $type = 'success', string $msg = '')
    {
        return back()->with('alert', [
            'type' => $type,
            'message' => $msg
        ]);
    }

    protected static function toWithAlert(string $name, ?array $params = null, string $type = 'success', string $msg = '')
    {
        return to_route($name, $params)->with('alert', [
            'type' => $type,
            'message' => $msg
        ]);
    }
}
