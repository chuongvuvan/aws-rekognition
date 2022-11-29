<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Post;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('post');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'nullable|sometimes|file',
            'description' => 'nullable|sometimes'
        ]);

        $newPost = auth()->user()->posts()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);

        if ($request->hasFile('image')) {
            $keyName = $request->file('image')->getClientOriginalName();

            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $filePath = $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            $rekognition = new RekognitionClient([
                'region' => 'us-east-2',
                'version' => 'latest',
            ]);

            // Get local image
            $photo = $keyName;
            $fp_image = fopen($file, 'r');
            $image = fread($fp_image, filesize($file));
            fclose($fp_image);

            // Call DetectFaces
            $result = $rekognition->DetectFaces(array(
                    'Image' => array(
                        'Bytes' => $image,
                    ),
                    'Attributes' => array('ALL')
                )
            );

            dd($result);
//            $client = new RekognitionClient([
//                'region' => getenv('AWS_DEFAULT_REGION'),
//                'version' => 'latest'
//            ]);
//
//            $image = fopen($request->file('image')->getPathname(), 'r');
//            $bytes = fread($image, $request->file('image')->getSize());
//
//            Post::faceMatch(file_get_contents('https://media.istockphoto.com/id/1322277517/photo/wild-grass-in-the-mountains-at-sunset.jpg?s=612x612&w=0&k=20&c=6mItwwFFGqKNKEAzv0mv6TaxhLN3zSE43bWmFN--J5w='));
//
//            $results = $client->detectModerationLabels([
//                'Image' => ['Bytes' => $bytes],
//                'MinConfidence' => 50,
//            ]);
//
//            $resultLabels = $results->get('ModerationLabels');
//
//            if ($resultLabels) {
//                $newPost->delete();
//                return redirect()->back()->withErrors(['nudity' => 'The image contained explicit nudity']);
//            }
//
//            $imagePath = $request->file('image')->store('public/posts');
//
//            if (!$imagePath) {
//                return redirect()->back()->withErrors(['image_upload_files' => 'The image upload failed']);
//            }
//
//            if (!$imagePath) {
//                return redirect()->back()->withErrors(['image_upload_files' => 'The image upload failed']);
//            }
//
//            $newPost->avatar_image = $imagePath;
//            $newPost->save();
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
