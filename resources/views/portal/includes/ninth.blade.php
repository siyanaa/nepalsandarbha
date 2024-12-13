<section>
    <div class="container">
        @foreach($relatedCats as $category)
        <div class="category-section">

            <p class="cat_title">{{ $category->title }}</p>

            <div class="row">

                @foreach($postsByCategory[$category->id] as $post)
                <div class="col-lg-4 col-sm-12 col-xs-12 col-md-12">


                    <a href="{{ route('post.render', ['slug' => $post->slug ?? '', 'id' => $post->id ?? '']) }}">
                        <div class="post_container">

                            <img class="square_image" src="{{ $category->firstImagePath }}">

                            <p><span class="post_title">
                                    {{ Str::substr($post->title, 0, 200) }}
                                </span>
                                <br>

                            </p>

                        </div>
                    </a>

                </div>
                @endforeach


            </div>
        </div>
        <hr class="dob_line">
        @endforeach
    </div>

</section>


<div class="container">
    <div class="top_ad">
        {{-- @if ($afterStrangeWorldAd)
        <div class="top_ad">
            <a target="_blank" href="{{ $afterStrangeWorldAd->url ?? '#' }}">
                <img src="{{ asset('uploads/images/ads/' . ($afterStrangeWorldAd->image ?? 'default.jpg')) }}" alt="">
            </a>
        </div>
        @else

        <p>No ad available.</p>
        @endif --}}
        @if ($afterStrangeWorldAd && $afterStrangeWorldAd->status == 0)
        <div class="top_ad">
            <a target="_blank" href="{{ $afterStrangeWorldAd->url ?? '#' }}">
                <img src="{{ asset('uploads/images/ads/' . ($afterStrangeWorldAd->image ?? 'default.jpg')) }}" alt="">
            </a>
        </div>
        @else
        <!-- Handle the case when no ad is found or the ad is not active -->
        <p>No active ad available.</p>
        @endif

    </div>
</div>
