@extends('portal.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="cat_title text_center">
                Posts tagged with "{{ $tag }}" <!-- Adjust as needed for "Rochak News" -->
            </p>
        </div>
        <!-- Include other necessary sections or ads -->

        @forelse ($posts as $post)
        <div class="col-md-4 mb-3">
            <a href="{{ route('post.render', ['slug' => $post->slug ?? '', 'id' => $post->id ?? '']) }}">
                <div class="tag_image">
                    <img src="{{ asset('uploads/images/post/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}">
                    <p class="tag_p">{{ $post->title ?? '' }}</p>
                </div>
            </a>
        </div>
        @empty
        <div class="col-md-12">
            <p>No posts found for the tag "{{ $tag }}".</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="row">
        <div class="col-md-12">
            {{ $posts->links() }}
        </div>
    </div>
</div>

@include('portal.includes.tenth')

<div class="container">
    <div class="top_ad">
        @if ($afterNavAd)
        <div class="top_ad">
            <a target="_blank" href="{{ $afterNavAd->url ?? '#' }}">
                <img src="{{ asset('uploads/images/ads/' . ($afterNavAd->image ?? 'default.jpg')) }}" alt="Ad">
            </a>
        </div>
        @else
        <p>No ad available.</p>
        @endif
    </div>
</div>
@endsection
