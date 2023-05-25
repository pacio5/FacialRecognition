<?php

namespace App\Http\Controllers;

use App\Models\AccessAttempt;
use App\Models\AuthorizedFace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;


class AuthorizedController extends Controller
{
    /**
     * Authenticated users only.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard.
     */
    public function dashboard(){
        Carbon::setLocale('it');

        $accesses = AccessAttempt::where('attempted_at', '>=', now()->subDays(7))->get();
        $accessesPerDay = $accesses->groupBy(function ($access) {
            return Carbon::parse($access->attempted_at)->format('Y-m-d');
        })->map(function ($accesses) {
            return $accesses->count();
        })->sortBy(function ($count, $date) {
            return $date;
        })->mapWithKeys(function($count, $date) {
            return [Carbon::parse($date)->formatLocalized('%A') => $count];
        });

        // Accessi degli ultimi 7 giorni
        $accessAttempts = AccessAttempt::where('attempted_at', '>=', now()->subDays(7))
            ->with('authorized_face')->paginate(10);
        return view('authorized.dashboard', [
            'accessAttempts' => $accessAttempts,
            'accessesPerDay' => $accessesPerDay,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authorizedFaces = AuthorizedFace::paginate(20);
        return view('authorized.index', [
            'authorizedFaces' => $authorizedFaces,
        ]);
    }


    /**
     * Revoke the authorization of the specified face.
     */
    public function revokeAuthorization(string $id)
    {
        $authorizedFace = AuthorizedFace::findOrFail($id);
        $authorizedFace->is_authorized = false;
        $authorizedFace->save();
        return redirect()->route('authorized.index')->with('success', 'Authorisation revoked');
    }

    /**
     * Authorize the specified face.
     */
    public function newAuthorize(string $id){
        $authorizedFace = AuthorizedFace::findOrFail($id);
        $authorizedFace->is_authorized = true;
        $authorizedFace->save();
        return redirect()->route('authorized.index')->with('success', 'Authorisation granted');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('authorized.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|required',
            'face_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        $input = $request->all();

        // Upload file to private storage
        $path = $request->file('face_file')->store(uniqid());


        $pathForScript = Storage::path($path);

        // Calculate face encoding with python script
        $response = Http::post(env("PYTHON_ENCODING_SCRIPT"), [
            'image_path' => $pathForScript,
        ]);

        $data = $response->json();
        $encodings = $data['encodings'];
        $input['encoding'] = pack('d*', ...$encodings);
        $input['authorized'] = isset($input['authorized']) && $input['authorized'] == "on";

        // Create the model and save it in the DB
        AuthorizedFace::create([
        'name' => $input['name'],
        'encoding' => $input['encoding'],
        'is_authorized' => $input['authorized'],
        'img_path' => $path
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $authorizedFace = AuthorizedFace::findOrFail($id)->load('access_attempts');
        return view('authorized.show', [
            'authorizedFace' => $authorizedFace,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $authorizedFace = AuthorizedFace::findOrFail($id);
        AccessAttempt::where('authorized_face_id', $authorizedFace->id)->delete();
        Storage::delete($authorizedFace->img_path);
        Storage::deleteDirectory(explode('/', $authorizedFace->img_path)[0]);
        
        $authorizedFace->delete();
        return redirect()->route('authorized.index')->with('success', 'Authorized face deleted');
    }
}
