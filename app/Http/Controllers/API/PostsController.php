<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResources;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class PostsController extends Controller
{

    use APIResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        $posts =PostResources::collection( Post::ALL());
        $posts = PostResources::collection(Post::paginate($this->paginate_number));

        return $this->fmtResponse($posts);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        /*logn way*/
        /*  if  (!$request->has('title') && $request->get('title') == ''){
              return $this->fmtResponse(null, 'post title is required', 301);

          }


          if  (!$request->has('body') && $request->get('body') == ''){
              return $this->fmtResponse(null, 'post body is required', 301);

          }*/


        /*smart validation way */
        /*   $validate = Validator::make($request->all(), [
               'title' => 'required',
               'body' => 'required',
           ]);


           if ($validate->fails()) {
               return $this->fmtResponse(null, $validate->errors(), 301);
           }
           */

        /*Smarter validation */
        $validation = $this->post_validator($request);
        if ($validation instanceof Response) {
            return $validation;
        }

        /*Create post */
        $post = Post::create($request->all());


        if ($post) {
            return $this->fmtResponse(new PostResources($post));

        }
        return $this->fmtResponse(null, 'Post not created ', 502);


    }

    private function post_validator($request)
    {

        /*smart validation way */
        $validate = Validator::make($request->all(), [
            'title' => 'required|unique:posts',
            'body' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->fmtResponse(null, $validate->errors(), 301);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $post = Post::find($id);

        if ($post) {
            return $this->fmtResponse(new PostResources($post));

        }
        return $this->notFound();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $post = Post::find($id);


        if (!$post) {
            return $this->notFound();

        }
        /*Smarter validation */
        $validation = $this->post_validator($request);
        if ($validation instanceof Response) {
            return $validation;
        }

        $post = $post->update($request->all());


        if (!$post) {
            return $this->fmtResponse(null, 'Post not updated ', 502);

        }
        return $this->fmtResponse(new PostResources($post));


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $post = Post::find($id);


        if (!$post) {
            return $this->notFound();

        }

        $delete = $post->delete();

        if (!$delete) {
            return $this->fmtResponse(null, 'Post not Deleted ', 502);

        }
        return $this->fmtResponse(null, 'Post Deleted !', 200);

    }
}
