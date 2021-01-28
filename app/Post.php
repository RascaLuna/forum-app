<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'content', 'title', 'image',
    ];

    public function category() {
        // 投稿は1つのカテゴリーに属する
        return $this->belongsTo(\App\Category::class, 'category_id');
    }

    public function user() {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function comments() {
        return $this->hasMany(\App\Comment::class, 'post_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(\App\Tag::class);
    }

    public static function exeStore($request)
    {
        $post = new Post;
            // $input = $request->only($post->getFillable());
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;
            $post->content = $request->content;
            $post->title = $request->title;

            $filename = $request->file('image')->store('public/image');

            $post->image = basename($filename);

            // contentからtagを抽出する
            preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->content, $match);

            $tags = [];
            foreach ($match[1] as $tag) {
                $found = Tag::firstOrCreate(['tag_name' => $tag]);

                array_push($tags, $found);
            }

            $tag_ids = [];
            foreach ($tags as $tag) {

                array_push($tag_ids, $tag['id']);
            }


            $post->save();
            $post->tags()->attach($tag_ids);
    }
}
