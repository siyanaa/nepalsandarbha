@extends('portal.master')

@section('content')
<style>
    .social_share {
        display: flex;
        align-items: center;
        margin-top: 15px;
        margin-bottom:15px;
    }

    .social_share a {
        margin-left: 10px;
        font-size: 24px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .social_share a:hover {
        transform: translateY(-3px);
    }

    .share_facebook {
        background-color: #3b5998;
        color: white;
    }

    .share_twitter {
        background-color: #1da1f2;
        color: white;
    }

    .share_viber {
        background-color: #665cac;
        color: white;
    }

    .share_whatsapp {
        background-color: #25d366;
        color: white;
    }

    #shareCount {
        font-size: 14px;
        color: #555;
        margin-right: 10px;
    }

    #shareCount i {
        margin-right: 5px;
        color: #666;
    }

    @media (max-width: 767px) {
        .row {
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            order: 1;
        }
        
        .sidebar {
            order: 2;
        }
        
        .facebook-comments {
            order: 3;
        }
    }
</style>

<div class="container">
    <p class="arrow"><a href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i><strong> Back</strong></a></p>

    @include('portal.includes.topAds')

    <div class="row">
        <div class="col-md-8 post_view main-content">
            <span class="tag_share">
                {{ $post->tags }}
            </span>

            <h3>{{ $post->title }}</h3>
            
            <p class="reporter_name"><strong> रिपोर्टर: </strong>{{ $post->reporter_name ?? 'नेपाल सन्दर्भ' }}</p>

            <span class="nep_date">
                @php
                    use Nilambar\NepaliDate\NepaliDate;

                    // Fetch the post's creation date
                    $createdAt = $post->created_at;

                    // Convert the creation date to Nepali date
                    $obj = new NepaliDate;
                    $nepaliDate = $obj->convertAdToBs($createdAt->year, $createdAt->month, $createdAt->day);
                    $info = $obj->getDetails($nepaliDate['year'], $nepaliDate['month'], $nepaliDate['day'], 'bs', 'np');

                    $dateDetail = $info['d'] . '-' . $info['F'] . '-' . $info['Y'];
                    
                    // Function to convert English numerals to Nepali numerals
                    function convertToNepaliNumerals($string) {
                        $nepali_numerals = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
                        return preg_replace_callback('/[0-9]/', function($match) use ($nepali_numerals) {
                            return $nepali_numerals[$match[0]];
                        }, $string);
                    }

                    // Convert time to Nepali numerals, keeping AM/PM as is
                    $timeDetail = convertToNepaliNumerals($createdAt->format('h:i:s')) . ' ' . $createdAt->format('A');

                    echo '<i class="fa fa-calendar" aria-hidden="true"></i> ' . $dateDetail . ', ' . $timeDetail;
                @endphp
            </span>

            <span class="social_share">
                <span id="shareCount">
                    <i class="fa fa-share-alt" aria-hidden="true"></i> {{ $post->shares }} Shares
                </span>
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
                    href="https://wa.me/?text={{ urlencode($post->title) }} {{ urlencode(Request::url()) }}"
                    target="_blank" onclick="trackShare({{ $post->id }})">
                    <i class="fab fa-whatsapp icon-large" aria-hidden="true"></i>
                </a>
            </span>

            @php
                try {
                    $images = json_decode($post->image, true);
                    $imagePath = isset($images[0]) ? $images[0] : null;
                } catch (\Exception $e) {
                    $imagePath = null;
                    \Log::error('Error decoding image JSON: ' . $e->getMessage());
                }
            @endphp

            @if ($imagePath)
                <div class="news-image-container">
                    <img src="{{ asset('uploads/posts/' . $imagePath) }}" class="post_view_img col-md-12" alt="{{ $post->title }}">
                </div>
            @else
                <p>No image available</p>
            @endif

            <div style="font-size:25px;">
                <p class="post_view_desc">{!! $post->content !!}</p>
            </div>
        </div>
        <div class="container facebook-comments order-1">
            <div id="fb-root">
                <div class="fb-comments" data-href="{{ url()->current() }}" data-width="600" data-numposts="100"></div>
            </div>
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0" nonce="PhQ7TiiA"></script>
        </div>
        <div class="col-md-4 sidebar">
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
                    @if($afterMainNewstitleAd)
                    <a target="_blank" href="{{ $afterMainNewstitleAd->url }}">
                        <img src="{{ asset('uploads/images/ads/' . $afterMainNewstitleAd->image) }}" alt="">
                    </a>
                @else
                    <p>No advertisement available.</p>
                @endif
                </div>
            </div>

            <div class="main_news p-4">
                <p class="cat_title">
                    सम्बन्धित खबर
                </p>
                <ul>
                    @foreach ($similarPosts as $similarPost)
                        <li>
                            <a style="text-decoration: none;" href="{{ route('post.render', ['slug' => $similarPost->slug ?? '', 'id' => $similarPost->id ?? '']) }}">
                                <p class="main_news_titles">{{ Str::substr($similarPost->title, 0, 200) ?? '' }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul> 
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

@if ($post->categories && $post->categories->contains('title', 'Photo Feature'))
<div class="other-images">
    @foreach (json_decode($photofeature->photo_feature) as $image)
    <a href="{{ asset('uploads/posts/' . $image) }}" class="venobox">
        <img src="{{ asset('uploads/posts/' . $image) }}" id="preview" style="width: 150px; height: 150px">
    </a>
    @endforeach
</div>
@endif

<hr>


<script>
    function trackShare(postId) {
        fetch('/increment-share-count', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ postId: postId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector('#shareCount').innerText = data.shareCount;
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection