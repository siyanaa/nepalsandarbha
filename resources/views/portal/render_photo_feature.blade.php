@extends('portal.master')

@section('content')
<div class="container">
    <p class="arrow"><a href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i><strong> Back</strong></a></p>

    @include('portal.includes.topAds')

    <div class="row">
        <div class="col-md-8 post_view">

            <span class="tag_share">
                {{ $post->tags }}
            </span>

            <h3>{{ $post->title }}</h3>

            <span class="nep_date">
                {{ $post->getTimeDifference() }}
            </span><br>
            <span class="nep_date">
                <i class="fa fa-calendar" aria-hidden="true"></i>{{ $post->getNepaliDate() }}
            </span>

            <span class="social_share">
                <a class="share_facebook"
                    href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}&display=popup"
                    target="_blank" onclick="trackShare({{ $post->id }})">
                    <i class="fa fa-facebook-official icon-large" aria-hidden="true"></i>
                </a>
                <a class="share_twitter"
                    href="https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}&text={{ urlencode($post->title) }}"
                    target="_blank" onclick="trackShare({{ $post->id }})">
                    <i class="fa fa-twitter icon-large" aria-hidden="true"></i>
                </a>
                <a class="share_viber"
                    href="https://viber.com/intent/viber?url={{ urlencode(Request::url()) }}&text={{ urlencode($post->title) }}"
                    target="_blank" onclick="trackShare({{ $post->id }})">
                    <i class="fab fa-viber icon-large" aria-hidden="true"></i>
                </a>
                <a class="share_whatsapp"
                    href="https://share_whatsapp.com/intent/share_whatsapp?url={{ urlencode(Request::url()) }}&text={{ urlencode($post->title) }}"
                    target="_blank" onclick="trackShare({{ $post->id }})">
                    <i class="fab fa-whatsapp icon-large" aria-hidden="true"></i>
                </a>
            </span>

            <img class="post_view_img col-md-12" src="{{ asset($post->image) }}" />

            <div style="font-size:25px;">
                <p class="post_view_desc">{!! $post->content !!}</p>
            </div>

            <script>
                function trackShare(postId) {
                    $.ajax({
                        type: 'POST',
                        url: '/post/{slug}/{id}',
                        dataType: 'json',
                        success: function(response) {
                            // Handle the response if needed
                        },
                        error: function(xhr, status, error) {
                            // Handle the error if needed
                        }
                    });
                }
            </script>

        </div>

        <div id="" class="col-md-4">
            <div class="main_news p-4">
                <p class="cat_title">
                    मुख्य समाचार
                </p>
                @foreach ($mukhyaNews as $mNews)
                <ul>
                    <li>
                        <a style="text-decoration: none;"
                            href="{{ route('post.render', ['slug' => $mNews->slug ?? '', 'id' => $mNews->id ?? '']) }}">
                            <p class="main_news_titles">
                                {{ Str::substr($mNews->title, 0, 200) ?? '' }}
                            </p>
                        </a>
                    </li>
                </ul>
                @endforeach
            </div>

            {{-- Right section Ads --}}
            <div>
                <div class="single_page_side">
                    <a target="_blank" href="{{ $afterMainNewstitleAd->url }}">
                        <img src="{{ asset('uploads/images/ads/' . $afterMainNewstitleAd->image) }}" alt="">
                    </a>
                </div>
            </div>

            <div class="main_news p-4">
                <p class="cat_title">
                    सम्बन्धित खबर
                </p>
                @foreach ($similarPosts as $similarPost)
                <ul>
                    <li>
                        <a style="text-decoration: none;"
                            href="{{ route('post.render', ['slug' => $similarPost->slug ?? '', 'id' => $similarPost->id ?? '']) }}">
                            <p class="main_news_titles">{{ Str::substr($similarPost->title, 0, 200) ?? '' }}</p>
                        </a>
                    </li>
                </ul>
                @endforeach
            </div>

            <div class="main_news p-4">
                <p class="cat_title">
                    अन्य खबर
                </p>
                @foreach ($tagPosts as $tagPost)
                <ul>
                    <li>
                        <a style="text-decoration: none;"
                            href="{{ route('post.render', ['slug' => $tagPost->slug ?? '', 'id' => $tagPost->id ?? '']) }}">
                            <p class="main_news_titles">{{ Str::substr($tagPost->title, 0, 200) ?? '' }}</p>
                        </a>
                    </li>
                </ul>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if ($post->category === 'Photo Feature')
<div class="other-images">
    @foreach (json_decode($photofeature->photo_feature) as $image)
    <a href="{{ asset('uploads/posts/' . $image) }}" class="venobox">
        <img src="{{ asset('uploads/posts/' . $image) }}" id="preview" style="width: 150px; height: 150px">
    </a>
    @endforeach
</div>
@endif

<hr>
<div class="container">
    <div id="fb-root">
        <div class="fb-comments" data-href="{{ url()->current() }}" data-width="600" data-numposts="100"></div>
    </div>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0" nonce="PhQ7TiiA"></script>
</div>

@include('portal.includes.bottomAds')
@endsection
