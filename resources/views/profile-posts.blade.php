<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Profile"> 
  <!-- sharedData comes from UserController -->
  <!-- doctitle comes from profile.blade.php -->

  @include('profile-posts-only')
  


</x-profile>
