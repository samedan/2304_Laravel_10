

<a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
  <img class="avatar-tiny" src="{{$post->user->avatar}}" />
  <strong>{{$post->title}}</strong> 
   <span class="text-muted small"> 
     @if(!isset($hideAuthor)) <!-- hideAuthor is a prop set on profile-posts.blade -->
     by <b>{{$post->user->username}}</b> 
     @endif
     on {{$post->created_at->format('n/j/Y')}}
    </span>
</a>
