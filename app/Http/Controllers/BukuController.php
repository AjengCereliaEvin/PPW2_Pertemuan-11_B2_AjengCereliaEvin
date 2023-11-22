<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; 
use App\Models\Gallery; 
use Image;

class BukuController extends Controller
{

    //fungsi index
    public function index() {
        $data_buku = Buku::all();
        // Buku::all( ) untuk menampilkan semua data buku pada tabel buku.

        // menghitung jumlah baris
        $jumlah_data = Buku::count();

        // menghitung total harga
        $total_harga = 0;
        foreach ($data_buku as $buku) {
            $total_harga = $total_harga +  (int)$buku->harga;
        }

        // paginate
        $batas = 5;
        $jumlah_buku = Buku::count();
        $data_buku = Buku::orderBy('id', 'asc')->paginate($batas);
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('auth.buku.index', compact('data_buku', 'jumlah_buku', 'total_harga', 'no'));
        //Compact( ) untuk mem-passing/mengirimkan variabel dari Controller ke View.

    }

    public function create() {
        // $buku = Buku::find($id);

        // $request->validate([
        //     'thumbnail' => 'image|mimes:jpeg,jpg,png'
        // ]);

        // if ($request->hasFile('thumbnail')) {
        //     // Jika ada, proses upload dan manipulasi gambar
        //     $fileName = time().'_'.$request->thumbnail->getClientOriginalName();
        //     $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');
    
        //     Image::make(storage_path().'/app/public/uploads/'.$fileName)
        //         ->fit(240, 320)
        //         ->save();
    
        //     // Update path dan nama file pada model
        //     $buku->create([
        //         'filename' => $fileName,
        //         'filepath' => '/storage/' . $filePath,
        //     ]);
        // }else{
        //     $buku->create([
        //         'filename' => 'src=""', // Ganti dengan nama file default yang diinginkan
        //         'filepath' => 'src=""', // Sesuaikan dengan path file default
        //     ]);
        // }
    
        // // Update data buku tanpa memperbarui thumbnail jika tidak diunggah
        // $buku->create([
        //     'judul' => $request->judul,
        //     'penulis' => $request->penulis,
        //     'harga' => $request->harga,
        //     'tgl_terbit' => $request->tgl_terbit,
            
        // ]);

        // if ($request->file('gallery')) {
        //     foreach($request->file('gallery') as $key => $file) {
        //         $fileNameGallery = time().'_'.$file->getClientOriginalName();
        //         $filePathGallery = $file->storeAs('uploads', $fileNameGallery, 'public');

        //         $gallery = Gallery::create([
        //             'nama_galeri'   => $fileNameGallery,
        //             'path'          => '/storage/' . $filePathGallery,
        //             'foto'          => $fileNameGallery,
        //             'buku_id'       => $id
        //         ]);
        //     }
        // }
        return view('auth.buku.create');
    }

    public function search(Request $request) {
        $batas = 5;
        $cari = $request->kata;
        $data_buku = Buku::where('judul', 'like',"%".$cari."%")->orwhere('penulis','like',"%".$cari."%")
            ->paginate($batas);
        $jumlah_buku = Buku::count();
        $total_harga = Buku::sum('harga');

        // Menghitung nomor urut berdasarkan halaman saat ini
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('auth.buku.search', compact('jumlah_buku', 'data_buku', 'no', 'cari'));
    }

    public function store(Request $request) {
        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = date('Y-m-d', strtotime($request->tgl_terbit));
        $buku->save(); 

        $this->validate($request,[
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date'
        ]);
        return redirect('/buku')->with('pesan','Data buku berhasil disimpan.');
    }

    public function destroy($id) {
        $buku = Buku::find($id);
        $buku->delete();
        return redirect('/buku')->with('pesan','Data buku berhasil dihapus');
    }

    

    public function edit($id) {
        $buku = Buku::find($id);
        return view('auth.buku.edit', compact('buku'));
        
    }


    public function update(Request $request, $id) {
        $buku = Buku::find($id);

        $request->validate([
            'thumbnail' => 'image|mimes:jpeg,jpg,png'
        ]);

        if ($request->hasFile('thumbnail')) {
            // Jika ada, proses upload dan manipulasi gambar
            $fileName = time().'_'.$request->thumbnail->getClientOriginalName();
            $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');
    
            Image::make(storage_path().'/app/public/uploads/'.$fileName)
                ->fit(140, 220)
                ->save();
    
            // Update path dan nama file pada model
            $buku->update([
                'filename' => $fileName,
                'filepath' => '/storage/' . $filePath,
            ]);
        }else{
            $buku->update([
                'filename' => 'src=""', // Ganti dengan nama file default yang diinginkan
                'filepath' => 'src=""', // Sesuaikan dengan path file default
            ]);
        }
    
        // Update data buku tanpa memperbarui thumbnail jika tidak diunggah
        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            
        ]);

        if ($request->file('gallery')) {
            foreach($request->file('gallery') as $key => $file) {
                $fileNameGallery = time().'_'.$file->getClientOriginalName();
                $filePathGallery = $file->storeAs('uploads', $fileNameGallery, 'public');

                $gallery = Gallery::create([
                    'nama_galeri'   => $fileNameGallery,
                    'path'          => '/storage/' . $filePathGallery,
                    'foto'          => $fileNameGallery,
                    'buku_id'       => $id
                ]);
            }
        }
        
        return redirect('/buku')->with('pesan','Perubahan Data Buku Berhasil Disimpan');
    }
    public function listBuku()
    {
        $bukus = Buku::all();

        return view('list_buku', compact('buku'));
    }

    public function galbuku($title){
        $bukus = Buku::where('buku_seo', $title)->first();
        $galeries = $bukus->photos()->orderBy('id', 'desc')->paginate(6);
        return view('galeri-buku', compact('$bukus', '$galeris'));
    }
}