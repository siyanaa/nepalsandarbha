@extends('admin.layouts.master')

@section('content')
@include('includes.forms')
<div class="card card-primary">
    <div class="card-header">
        <h1 class="card-title">Add Post</h1>
        <a style="float: right;" href="/admin/posts/index"><button class="btn btn-success"><i class="fas fa-backward"></i> Back</button></a>
    </div>

    @if(session('errorMessage'))
    <div class="alert alert-danger">
        {{ session('errorMessage') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="quickForm" method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="title">Title</label><span style="color:red; font-size:large"> *</span>
                <input style="width:auto;" type="text" name="title" class="form-control" id="title" placeholder="Title" value="{{ old('title') }}" required>
            </div>
            <div>
                <label for="description">Description</label><span style="color:red; font-size:large"> *</span>
                <textarea style="max-width: 30%;" type="text" class="form-control" name="description" id="description" placeholder="Add Description" required>{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" name="tags" class="form-control" id="tags" placeholder="Tags" value="{{ old('tags') }}">
            </div>
            <div>
                <label for="content">Content:</label>
                <textarea name="content" id="content" cols="30" rows="10">{{ old('content') }}</textarea>
            </div>
            <div class="form-group">
                <label for="images">Images <span style="color:red;"></span></label><span style="color:red; font-size:large"> *</span>
                <input type="file" name="image[]" class="form-control" multiple onchange="previewImages(event)" required>
            </div>
            <div id="imagePreviews" class="row">
                @foreach (old('image', []) as $uploadedImage)
                <div class="col-md-2 mb-3 image-preview">
                    <img src="{{ $uploadedImage }}" alt="Image Preview" class="img-fluid">
                    <span class="remove-image" onclick="removePreview(this)">Remove</span>
                </div>
                @endforeach
            </div>
            <div class="form-group">
                <label for="reporter">Reporter</label>
                <input type="text" name="reporter_name" class="form-control" id="reporter" placeholder="Reporter Name" value="{{ old('reporter_name') }}">
            </div>
            <div style="display: flex;">
                <div class="form-group" style="margin: auto;">
                    <label>Sections</label>
                    @foreach ($sections as $section)
                    <div class="form-check checkbox1">
                        <input class="form-check-input" name="sections[]" value="{{ $section->id }}" type="checkbox" {{ in_array($section->id, old('sections', [])) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $section->title ?? '' }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="form-group" style="margin: auto;">
                    <label>Categories</label>
                    @foreach ($categories as $category)
                    <div class="form-check checkbox2">
                        <input class="form-check-input" name="categories[]" value="{{ $category->id }}" type="checkbox" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    // Ensure content is properly encoded
                    $('#content').val(contents);
                }
            }
        });
    </script>

    <style>
        .note-editor .note-editable {
            font-family: Arial, sans-serif;
        }
        .note-editor .note-editable strong, 
        .note-editor .note-editable b {
            font-weight: bold !important;
        }
    </style>
</div>
@endsection