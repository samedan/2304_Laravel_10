<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Profile"> 
  <!-- sharedData comes from UserController -->
  <!-- doctitle comes from profile.blade.php -->

  <div class="list-group">
    @foreach ($posts as $post)
      <x-post :post="$post" hideAuthor /> <!-- load /views/components/post.blade -->
    @endforeach
    </div>


</x-profile>
