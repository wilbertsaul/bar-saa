<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditoriaLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditoriaController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditoriaLog::with('user')->orderByDesc('realizado_en');

        if ($request->filled('modelo')) {
            $query->where('modelo', $request->modelo);
        }
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('realizado_en', $request->fecha);
        }

        $logs = $query->paginate(50)->withQueryString();

        $modelos = AuditoriaLog::distinct()->pluck('modelo');
        $acciones = AuditoriaLog::distinct()->pluck('accion');

        return view('admin.auditoria.index', compact('logs', 'modelos', 'acciones'));
    }
}
