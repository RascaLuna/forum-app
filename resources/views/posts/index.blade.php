
@extends('layouts.app')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('/js/ajax.js') }}" defer></script>

@section('content')
<div class="card-body">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-title">検索フォーム</h5>
                <div id="custom-search-input">
                    <div class="input-group col-md-12 search-form">
                        <form action="{{ route('posts.search') }}" method="get">
                            {{ csrf_field() }}
                            <input type="text" id = "search-word" class="form-control input-lg" placeholder="Buscar" name="search">
                            <span class="input-group-btn" style="position: relative; top:-33px; right: -185px;">
                                {{-- buttonタグのtypeをbuttonに変更する --}}
                                <button class="btn btn-info search-icon" type="button">
                                    {{-- イベントの起点 --}}
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-header">Board</div>

{{--  この変数が存在する場合のみ検索結果数を表示する  --}}
@isset ($search_result)
    <h5 class="card-title">{{ $search_result }}</h5>
@endisset

{{-- id search-resultにJavaScriptから差し込む --}}
<div id = "search-result" class="card-body">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @foreach($posts as $post)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $post->title }}</h5>
            <h5 class="card-title">
                カテゴリー：
                <a href="{{ route('posts.index', ['category_id' => $post->category-> id]) }}">
                    {{ $post->category->category_name }}
                </a>
            </h5>
            <h5 class="card-title">
                Tag：
                @foreach($post->tags as $tag)
                    <a href="{{ route('posts.index', ['tag_name' => $tag->tag_name]) }}">
                        #{{ $tag->tag_name }}
                    </a>
                @endforeach
            </h5>
            <h5 class="card-title">
                投稿者：
                <a href="{{ route('users.show', $post->user_id) }}">{{ $post->user->name }}</a>
            </h5>
            <p class="card-text">{{ $post->content }}</p>
            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細</a>
        </div>
    </div>
    @endforeach
</div>

@if (isset($category_id))
    {{ $posts->appends(['category_id' =>$category_id])->links() }}
@elseif (isset($tag_name))
    {{ $posts->appends(['tag_name' =>$tag_name])->links() }}
@elseif (isset($search_query))
    {{ $posts->appends(['search' => $search_query])->links() }}
@else
    {{ $posts->links() }}
@endif

@endsection
