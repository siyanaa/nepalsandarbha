@extends('admin.layouts.master')

<!-- Main content -->
@section('content')
@include('includes.forms')
<div class="card card-primary">
    <div class="card-header">
        <h1 class="card-title">Update Post</h1>
        <a style="float: right;" href="/admin/posts/index"><button class="btn btn-success"><i class="fas fa-backward"></i>
                Back</button></a>
    </div>

    @if(session('errorMessage'))
    <div class="alert alert-danger">
        {{ session('errorMessage') }}
    </div>
@endif

    <form id="quickForm"  method="POST" action="{{ route('admin.posts.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $post->id }}">
        <div class="card-body">
            <div class="form-group">
                <label for="title">Title</label><span style="color:red; font-size:large"> *</span>
                <input style="width:auto;" type="text" value="{{ $post->title ?? '' }}" name="title" class="form-control" id="title" placeholder="Title" required>
            </div>
            <div>
                <label for="registration_date">Description</label><span style="color:red; font-size:large">
                    *</span>
                <textarea style="max-width: 30%;" type="text" class="form-control" name="description" id="description" placeholder="Add Description" required>{!! $post->description ?? '' !!}</textarea>
            </div>
            <div class="form-group">
                <label for="address">Tags</label><span style="color:red; font-size:large"> </span>
                <input type="text" name="tags" value="{{ $post->tags ?? '' }}" class="form-control" id="address" placeholder="Tags">
            </div>
            {{-- <div class="form-group"> --}}
                {{-- <label for="taxpayer_name">Content</label><span style="color:red; font-size:large"> *</span> --}}
                <div>



                    {{-- <label for="content">Content</label><span style="color:red; font-size:large">
                        *</span>
                    <textarea style="max-width: 100%;min-height: 250px;" type="text" class="form-control" id="myTextarea"
                        name="content" placeholder="Add Description">
                    {{ old('content') }}
                    </textarea> --}}

                    <label for="">Content:</label>
                    <textarea name="content" id="content" cols="30" rows="10">{{ $post->content }}</textarea>

                </div>


                {{-- <textarea style="max-width: 100%;min-height: 250px;" type="text" class="form-control" id="myTextarea"
                name="content" placeholder="Add Description">{!! $post->content ?? '' !!}</textarea> --}}
                {{-- <textarea type="text" name="content" class="form-control" id="content" placeholder="Content" rows="10" cols="50">{!! $post->content ?? '' !!}</textarea> --}}
            {{-- </div> --}}

            <div class="form-group">
                <label for="exampleInputEmail1">Images <span style="color:red;"></span></label>
                <span style="color:red; font-size:large"> *</span>

                <input type="file" name="image[]" id="" class="form-control" multiple onchange="previewImages(event)">

                {{-- @foreach ($post->image as $image )
                    <img src="{{ $image }}" alt="Post Image">
                @endforeach --}}


            {{-- @foreach ($post->image as $uploadedImage)
                <div class="col-md-2 mb-3 image-preview">
                    <img src="{{ $uploadedImage }}" alt="Image Preview" class="img-fluid">
                    <span class="remove-image" onclick="removePreview(this)">Remove</span>
                </div>
            @endforeach --}}


                {{-- <div id="imagePreviews" class="row">
                    @foreach ($post->image as $image)
                        <div class="col-md-2 mb-3 image-preview">
                            <img src="{{ $image }}" alt="Image Preview" class="img-fluid">
                            <span class="remove-image" onclick="removePreview(this)">Remove</span>
                        </div>
                    @endforeach
                </div> --}}




            </div>

            <div id="imagePreviews" class="row">
                @foreach (old('image', []) as $uploadedImage)
                <div class="col-md-2 mb-3 image-preview">
                    <img src="{{ $uploadedImage }}" alt="Image Preview" class="img-fluid">
                    <span class="remove-image" onclick="removePreview(this)">Remove</span>
                </div>
            @endforeach
            </div>


            {{-- <img src="{{ url('storage/'.$post->image) ?? '' }}" id="preview" style="max-width: 500px; max-height:500px" /> --}}



            <div class="form-group">
                <label for="reporter">Reporter Name</label>
                <input style="width:auto;" type="text" value="{{ $post->reporter_name ?? '' }}" name="reporter_name" class="form-control" id="reporter" placeholder="Reporter Name">
            </div>


            <div style="display: flex;">
                <div class="form-group" style="margin: auto;">
                    <label>Sections</label>
                    @foreach ($sections as $section)
                    <div class="form-check checkbox1">
                        <input class="form-check-input" name="sections[]" value="{{ $section->id }}" type="checkbox" @if ($post->getSections->contains($section->id))
                        checked
                        @endif >
                        <label class="form-check-label">{{ $section->title ?? '' }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="form-group" style="margin: auto;">
                    <label>Categories</label>
                    @foreach ($categories as $category)
                    <div class="form-check checkbox2">
                        <input class="form-check-input" name="categories[]" value="{{ $category->id }}" type="checkbox" @if ($post->getCategories->contains($category->id))
                        checked
                        @endif>
                        <label class="form-check-label">{{ $category->title ?? '' }}</label>
                    </div>
                    @endforeach
                </div>
            </div>



        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

    <script>
        const previewImages = e => {
            const files = e.target.files;
            const imagePreviews = document.getElementById('imagePreviews');
            imagePreviews.innerHTML = ''; // Clear existing previews

            for (const file of files) {
                const reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = () => {
                    const preview = document.createElement('div');
                    preview.className = 'col-md-2 mb-3';
                    preview.innerHTML = `<img src="${reader.result}" alt="Image Preview" class="img-fluid">`;
                    imagePreviews.appendChild(preview);
                };
            }
        };


    </script>


<script>
    $('#content').summernote({
        placeholder: 'content...',
        tabsize:2,
        height:300
    });
</script>






@endsection
