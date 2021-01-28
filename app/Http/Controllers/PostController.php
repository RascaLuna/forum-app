<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use App\User;
use App\Tag;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ポストテーブルのデータを全件取得

        $q = \Request::query();

        if (isset($q['category_id'])) {
            $posts = Post::latest()->where('category_id', $q['category_id'])->paginate(3);
            $posts->load('category', 'user', 'tags');

            return view(
                'posts.index',
            [
                'posts' =>$posts,
                'category_id' => $q['category_id']
            ]);

        } elseif (isset($q['tag_name'])) {
            $posts = Post::latest()->where('content', 'like', "%{$q['tag_name']}%")->paginate(3);
            $posts->load('category', 'user', 'tags');

            return view(
                'posts.index',
            [
                'posts' =>$posts,
                'tag_name' => $q['tag_name']
            ]);


        } else {
            $posts = Post::latest()->paginate(3);
            $posts->load('category', 'user', 'tags');

            return view('posts.index',
            [
                'posts' => $posts,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create',
        [

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        if ($request->file('image')->isValid()) {
            // Post.modelから投稿処理の呼び出し
            Post::exeStore($request);
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
        $post->load('category', 'user', 'comments.user');

        return view('posts.show',
        [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // laravel(同期通信)の検索機能
    // public function search(Request $request)
    // {
    //     $posts = Post::where('title', 'like', "%{$request->search}%")
    //             ->orwhere('content', 'like', "%{$request->search}%")
    //             ->paginate(5);

    //     $search_result = $request->search.'の検索結果'.$posts->total().'件';

    //     return view('posts.index', [
    //         'posts' => $posts,
    //         'search_result' => $search_result,
    //         'search_query' => $request->search,
    //     ]);
    // }

    // ajax(非同期通信)の検索機能
    public function ajaxSearch(Request $request)
    {
        $posts = Post::where('title', 'like', "%{$request->search}%")
                        ->orwhere('content', 'like', "%{$request->search}%")
                        ->paginate(5);
        $posts->join('tags');
        $category = [];
        $user = [];


        foreach ($posts as $value) {
            $category[$value->category_id] = Category::where('id', $value->category_id)->pluck('category_name')->toArray();
        }
        foreach ($posts as $value) {
            $user[$value->user_id] = User::where('id', $value->user_id)->pluck('name')->toArray();
        }


        \Debugbar::info($posts);
        \Debugbar::info($category);
        \Debugbar::info($user);



        // categoryテーブルから取得したcategory_nameをposts配列に新しく挿入する
        foreach ($category as $key => $valA) {
            foreach ($valA as $valB) {
                for ($i=0; $i < count($posts); $i++) {
                    if ($posts[$i]['category_id'] === $key) {
                        $posts[$i]['category_name'] = $valB;
                    }
                }
                // \Debugbar::info($key, $valB);
            }
        }
        // userテーブルから取得したnameをposts配列に新しく挿入する
        foreach ($user as $key => $valA) {
            foreach ($valA as $valB) {
                for ($i=0; $i < count($posts); $i++) {
                    if ($posts[$i]['user_id'] === $key) {
                        $posts[$i]['user_name'] = $valB;
                    }
                }
                // \Debugbar::info($key, $valB);
            }
        }



        \Debugbar::info($posts);

        return response()->json($posts);
    }
}
