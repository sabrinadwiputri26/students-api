<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
//import                    
use App\Helpers\ApiFormatter;
use Exception;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //ambil data dati key search_nama bagian params nya postman
        $search = $request->search_nama;
        $limit = $request->limit;
        //cari data berdasarkan yang di search

        $students = Student::where('nama', 'LIKE', '%'.$search.'%')->limit($limit)->get();

        //ambil semua data melalui model
        // $students = Student::all();

        //ambil semua data berhasil diambil
        if ($students) {
            return ApiFormatter::createAPI(200, 'succes', $students);
        }else {
            //kalau data gagal diambil
            return ApiFormatter::createAPI(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|min:3',
                'nis' => 'required|numeric',
                'rombel' => 'required',
                'rayon' => 'required',
            ]);
            //ngirim data atau tambah data baru ke table students lewat model student
            $student = Student::create([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'rombel' => $request->rombel,
                'rayon' => $request->rayon,
            ]);
            //cari data baru yg berhsail disimpan, cari berdasarkan id lewat data id dari student
            $hasilTambahData = Student::where('id', $student->id)->first();
            if ($hasilTambahData) {
                return ApiFormatter::createAPI(200, 'succes', $student);
            }else {
                //kalau data gagal diambil
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch(Exception $error) {
            //munculin deskripsi error yg bakal tampil di property data json
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function createToken()
    {
        return csrf_token();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //ambil data table yang id nya sama kaya $id dari path routenya
        try{
            $student = Student::find($id);
            if($student) {
                return ApiFormatter::createAPI(200, 'succes', $student);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        } 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|min:3',
                'nis' => 'required|numeric',
                'rombel' => 'required',
                'rayon' => 'required',
            ]);
            //ambil data yang akan diubah
            $student = Student::find($id);
            //update data yang 
            $student->update([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'rombel' => $request->rombel,
                'rayon' => $request->rayon,
            ]);
            $dataTerbaru = Student::where('id', $student->id)->first();
            if ($dataTerbaru) {
                return ApiFormatter::createAPI(200, 'success', $dataTerbaru);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }     
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
        $student = Student::find($id);
        $cekBerhasil = $student->delete();
        if ($cekBerhasil) {
            return ApiFormatter::createAPI(200, 'success','Data terhapus!');  
        }else {
            return ApiFormatter::createAPI(400, 'failed');  
        }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function trash()
    {
        try {
            $students = Student::onlyTrashed()->get();

            if ($students) {
                return ApiFormatter::createAPI(200, 'success', $students);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $student = Student::onlyTrashed()->where('id', $id);

            $student->restore();
            $dataKembali = Student::where('id', $id)->first();
            if ($dataKembali) {
                return ApiFormatter::createAPI(200, 'success', $dataKembali);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try {
            $student = Student::onlyTrashed()->where('id', $id);

            $proses = $student->forceDelete();
                return ApiFormatter::createAPI(200, 'success', 'Berhasil hapus permanent!');
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}


