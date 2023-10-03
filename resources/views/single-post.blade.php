<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
        <div class="d-flex justify-content-between">
          <h2>{{ $post->title }}</h2>
          @can('update', $post) {{-- policy with update --}}
            <span class="pt-2">
              
              {{-- Edit post URL button --}}
              <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
              
              {{-- Delete post button --}}
              <form class="delete-post-form d-inline" action="/post/{{ $post->id }}" method="POST">
                @csrf
                @method('DELETE') {{-- Used to delete data from the server from URL example "post/1" or action="/post/{{ $post->id }}" --}}
                <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
              </form>

            </span>
          @endcan
        </div>
  
        <p class="text-muted small mb-4">
          <a href="/profile/{{$post->user->username}}"><img class="avatar-tiny" src="{{$post->user->avatar}}" /></a>
          Posted by <a href="/profile/{{$post->user->username}}">{{ $post->user->username }}</a> on {{ $post->created_at->format('n/j/Y') }}
          {{-- $post brings the previously configured ID value and will only access the fields or functions ( function user() ) in that Post model. $post will have to match user_id field to access the username of this post based on 'id' --}}
        </p>
  
        <div class="body-content">
          {{-- {!! $post->body !!} --}}
          {{ $post->body }}
          {{-- body column in posts table --}}
        </div>
    </div>
</x-layout>

    
