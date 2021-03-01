<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['posts'] = Post::orderBy('id','asc')->paginate(5);
    
        return view('posts.index', $data);
    }
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'imagem' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'musica'=>'required',
            'descricao' => 'required',
            

            
        ]);
        $path = $request->file('imagem')->store('public/imagens');
        $path2 = $request->file('musica')->store('public/musicas');
        $post = new Post;
        $post->titulo = $request->titulo;
        $post->descricao = $request->descricao;
        $post->imagem = $path;
        $post->musica = $path2;
        $post->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(30), FFMpeg\Coordinate\TimeCode::fromSeconds(15));
        $post->save();





     
        return redirect()->route('posts.index')
                        ->with('success','Post has been created successfully.');
    }
     
    /**
     * Display the specified resource.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit',compact('post'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required',
            'descricao' => 'required',
        ]);
        
        $post = Post::find($id);
        if($request->hasFile('imagem')){
            $request->validate([
              'imagem' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $path = $request->file('imagem')->store('public/imagens');
            $post->image = $path;
        }
        $post->titulo = $request->titulo;
        $post->descricao = $request->descricao;
        $post->save();
    
        return redirect()->route('posts.index')
                        ->with('success','Post updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
    
        return redirect()->route('posts.index')
                        ->with('success','Post has been deleted successfully');
    }
    
}
