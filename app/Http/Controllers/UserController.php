<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $per_page = $request->per_page ?? 20;
        try {
            return User::where(function($query) use($search){
                return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            })->orderByDesc('created_at')->paginate($per_page);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                "message" => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
            ]);

            return response()->json([
                "message" => "Berhasil Menambah Data",
                "data" => $user
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message" => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $user = User::find($id);

            if ($user) {
                return response()->json([
                    "message" => "Berhasil Mendaptakan Data",
                    "data" => $user
                ], 200);
            } else {
                return response()->json([
                    "message" => "Data Tidak DItemukan",
                    "data" => []
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message" => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);

        try {

            $user = User::find($id);

            if ($user) {
                $data = $user->update([
                    'name' => $request->name,
                    'email' => $request->email
                ]);
                return response()->json([
                    "message" => "Berhasil Mengubah Data",
                    "data" => $data
                ], 200);
            } else {
                return response()->json([
                    "message" => "Data Tidak DItemukan",
                    "data" => []
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message" => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $user = User::find($id);

            if ($user) {
                $user->delete();
                return response()->json([
                    "message" => "Berhasil Menghapus Data",
                    "data" => []
                ], 200);
            } else {
                return response()->json([
                    "message" => "Data Tidak DItemukan",
                    "data" => []
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message" => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }
}
