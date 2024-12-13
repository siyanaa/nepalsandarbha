<section>

    <div class="container">
        <a href="{{ route('category.render', [$categories[8]->slug, $categories[8]]) }}">
            <p class="cat_title">

                {{ $categories[8]->title }}
            </p>
        </a>


        <div class="row">


            @foreach($eighthRow as $rowOne)
            <div class="col-lg-4 col-sm-12 col-xs-12 col-md-12">



                <a href="{{ route('post.render', ['slug' => $rowOne->slug ?? '', 'id' => $rowOne->id ?? '']) }}">
                    <div class="post_container">

                        <img class="round_image" src="{{ $rowOne->firstImagePath }}">

                        <p><span class="post_title">
                                {{ Str::substr($rowOne->title, 0, 200) }}
                            </span>
                            <br>

                        </p>

                    </div>
                </a>


            </div>
            @endforeach

        </div>
        <hr class="dob_line">
    </div>

</section>
