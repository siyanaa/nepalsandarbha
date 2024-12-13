<?php

namespace App\Http\Controllers\Admin;

use Exception;
use DOMDocument;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UtilityFunctions;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Gate::allows('hasPermission', 'view_posts'), 403);


        $posts = Post::with('getCategories', 'getSections')->latest()->paginate(20);

        return view('admin.post.index', ['posts' => $posts]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_unless(Gate::allows('hasPermission', 'create_posts'), 403);

        $categories = Category::all();
        $sections = Section::all();
        return view('admin.post.create', ['categories' => $categories, 'sections' => $sections]);
    }

    // FUNCTION TO CONVERT IMAGE

    private function convertImage($image)
    {


        $fileName = uniqid() . '.webp';
        $imagePath = 'uploads/posts/' . $fileName;

        $directory = public_path('uploads/posts/');

        try {
            if (!File::isDirectory($directory)) {
                $created = File::makeDirectory($directory, 0755, true, true);

                if (!$created) {
                    throw new \Exception('Failed to create directory: ' . $directory);
                }
            }

            $img = Image::make($image->getRealPath());
            $img->encode('webp', 70)->save(public_path($imagePath));

            return $imagePath;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function convertImages($images)
    {
        $ext = 'webp';
        $news = [];

        foreach ($images as $image) {
            $image_name = hexdec(uniqid()) . '-' . time() . '.' . $ext;
            $image_destination_path = public_path('uploads/posts/') . $image_name;
            $image_convert = Image::make($image->getRealPath());
            $image_convert->save($image_destination_path, 50);
            $news[] = $image_name;
        }

        return $news;
    }



    private function processTags($tags)
    {
        if (empty($tags)) {
            return '';
        }

        $tagsArray = explode(',', $tags);
        $formattedTags = [];

        foreach ($tagsArray as $tag) {
            $formattedTags[] = '#' . trim($tag);
        }

        return implode(',', $formattedTags);
    }



    public function store(Request $request)
    {
        abort_unless(Gate::allows('hasPermission', 'create_posts'), 403);

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:6000', // maximum file size of 6 MB
            'categories' => 'required',
            'sections' => 'sometimes',
            'reporter_name' => 'nullable',
        ]);



        try {
            $post = new Post;
            $post->title = $request->title;
            $post->description = $request->description;

            $post->content = $this->postSummernote($request->content);

            // $content = $request->content;
            // $strippedContent = preg_replace('/<(?!p\b)[^>]*>/', '', $content);
            // $post->content = $strippedContent;

            $post->tags = $this->processTags($request->tags);
            $post->slug = Str::slug(substr($request->title, 0, 500));

            $post->image = $request->hasFile('image') ? json_encode($this->convertImages($request->file('image'))) : [];
            $post->reporter_name = $request->reporter_name;



            if ($post->save()) {
                $post->getCategories()->sync($request->categories);
                $post->getSections()->sync($request->sections);
                UtilityFunctions::createHistory('Created Post with Id ' . $post->id . ' and title ' . $post->title, 1);

                return redirect()->route('admin.posts.index', ['slug' => $post->slug, 'id' => $post->id])->with([
                    'successMessage' => 'Success!! Post created',
                ]);
            } else {
                return redirect()->back()->with(['errorMessage' => 'Error!! Post not created']);
            }
        } catch (Exception $e) {

            return redirect()->back()->with(['errorMessage' => $e->getMessage()]);
        }
    }



    public function postSummernote($details)
    {
        if ($details != '') {
            $dom = new \DomDocument();

            // this will  escape the unescaped-xml
            //<w:View>Normal</w:View>
            //&lt;w:View&gt;Normal&lt;/w:View&gt;
            $details = preg_replace('/<(\w+):(\w+)>/', '&lt;\1:\2&gt;', $details);
            $details = preg_replace('/<\/(\w+):(\w+)>/', '&lt;/\1:\2&gt;', $details);

            libxml_use_internal_errors(true);
            $dom->loadHtml('<meta http-equiv="Content-Type" content="charset=utf-8" />' . $details);
            libxml_clear_errors();
            $images = $dom->getElementsByTagName('img');
            // foreach <img> in the submited message
            foreach ($images as $img) {
                $src = $img->getAttribute('src');
                $src = str_replace('http://127.0.0.1:8000/', 'http://127.0.0.1:8000/', $src);
                $img->removeAttribute('src');
                $img->setAttribute('src', $src);

                // if the img source is 'data-url'
                if (preg_match('/data:image/', $src)) {
                    // get the mimetype
                    preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                    $mimetype = $groups['mime'];
                    // dd($mimetype);
                    // Generating a random filename
                    $filename = uniqid();
                    $filepath = 'uploads_old/content/' . $filename . '.' . $mimetype;
                    // $filepath = 'assets/uploads/content/' . $filename . '.' . $mimetype;
                    // $filepath = str_replace('/', DIRECTORY_SEPARATOR, $filepath);

                    // dd($filepath);
                    // $filepath = public_path('assets/uploads/content/') . $filename;

                    // dd(public_path());
                    // @see http://image.intervention.io/api/
                    $image = Image::make($src)
                        // resize if required
                        //->resize(300, 200)
                        ->encode($mimetype, 100)  // encode file to the specified mimetype
                        ->save(public_path($filepath));
                        // dd($image);
                    $new_src = asset($filepath);
                    $img->removeAttribute('src');
                    $img->setAttribute('src', $new_src);
                }
            }
            // dd('abc');
            $html_cut = preg_replace('~<(?:!DOCTYPE|/?(?:html|body|head|meta))[^>]>\s~i', '', $dom->saveHTML());
            return $html_cut;
        } else {
            return $details;
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_unless(Gate::allows('hasPermission', 'update_posts'), 403);

        $post = Post::find($id);
        $categories = Category::all();
        $sections = Section::all();
        return view('admin.post.update', ['post' => $post, 'categories' => $categories, 'sections' => $sections]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        abort_unless(Gate::allows('hasPermission', 'update_posts'), 403);

        $this->validate($request, [
            'id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // maximum file size of 2 MB
            'categories' => 'required',
            'reporter_name' => 'nullable',
        ]);

        try {
            $post = Post::find($request->id);
            $post->title = $request->title;
            $post->description = $request->description;
            $post->content = $this->postSummernote($request->content);

            // Process and update Summernote content with images


            $post->tags = $this->processTags($request->tags);
            $post->slug = Str::slug(substr($request->title, 0, 500));

            // Update the image attribute only if new images are selected
            $post->image = $request->hasFile('image') ? json_encode($this->convertImages($request->file('image'))) : $post->image;

            // Continue with the rest of your update logic...

            if ($post->save()) {
                $post->getCategories()->sync($request->categories);
                $post->getSections()->sync($request->sections);
                UtilityFunctions::createHistory('Updated Post with Id ' . $post->id . ' and title ' . $post->title, 1);
                return redirect()->route('admin.posts.index')->with(['successMessage' => 'Success!! Post Updated']);
            } else {
                return redirect()->back()->with(['errorMessage' => 'Error!! Post not Updated']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_unless(Gate::allows('hasPermission', 'delete_posts'), 403);

        try {
            $post = Post::find($id);

            $dom = new DOMDocument();
            $dom->loadHTML($post->content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $images = $dom->getElementsByTagName('img');


            foreach ($images as $key => $img) {

                $src = $img->getAttribute('src');
                $path = Str::of($src)->after('/');


                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            if ($post->delete()) {

                $post->getCategories()->detach();
                $post->getSections()->detach();

                UtilityFunctions::createHistory('Deleted Ad with Id ' . $post->id . ' and title ' . $post->title, 1);
                return redirect()->route('admin.posts.index')->with(['successMessage' => 'Success !! Post Deleted']);
            } else {
                return redirect()->back()->with(['errorMessage' => 'Error Post not Deleted']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['errorMessage' => $e->getMessage()]);
        }
    }

    // public function uploadImage(Request $request)
    // {

    //     $fileName = $request->file('file')->getClientOriginalName();
    //     $path = $request->file('file')->storeAs('uploads/tiny', $fileName, 'public');
    //     return response()->json(['location' => "/storage/$path"]);

    //     $imgpath = request()->file('file')->store('uploads/tiny/', 'public');
    //     return response()->json(['location' => "/storage/$imgpath"]);
    // }
}
