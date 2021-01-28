

window.onload = function() {
    $('.search-form .search-icon').on('click', function () {
        $('#search-result .card-body').empty();
        $('.search-null').remove();
        $('#search-result .card').remove();

        let searchWord = $('#search-word').val(); //検索ワードを取得

        if(!searchWord) {
            return false;
        }

        $.ajax({
            type: 'GET',
            url: '/posts/search/',
            data: {
                'search': searchWord,
            },
            dataType: 'json',
            timeout: 3000,

            beforeSend: function () {
                $('.loading').removeClass('display-none');
            }

        }).done(function (data) {

            $('.loading').addClass('display-none');
            var data = Object.entries(data);

            // laravelから受け取ったdataが反映されているか確認
            // console.log(data);

            // tag_name取得の確認
            // console.log(data[1][1][0].tags[0]["tag_name"]);

            // console.log(data[1][1]);

            for (let i = 0; i < data[1][1].length; i++) {
                var posts = [];
                posts[i] =  `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${data[1][1][i]["title"]}</h5>
                                        <h5 class="card-title">
                                            カテゴリー：
                                                <a href="{{ route('posts.index', ['category_id' => $post->category-> id]) }}">
                                                    ${data[1][1][i]["category_name"]}
                                                </a>
                                        </h5>
                                        <h5 class="card-title">
                                            Tag：
                                                <a href="{{ route('posts.index', ['tag_name' => $tag->tag_name]) }}">
                                                #${data[1][1][i].tags[0]["tag_name"]}
                                                </a>
                                        </h5>
                                        <h5 class="card-title">
                                            投稿者：
                                            <a href="{{ route('users.show', $post->user_id) }}">${data[1][1][i]["user_name"]}</a>
                                        </h5>
                                        <p class="card-text">${data[1][1][i]["content"]}</p>
                                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細</a>
                                    </div>
                                </div>
                            `;
                            console.log(posts[i]);
                            $('#search-result').append(posts[i]);

            }

        }).fail(function () {
            alert('通信に失敗');
        })
    })
}

