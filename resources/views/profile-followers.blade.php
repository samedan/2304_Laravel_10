<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Followers"> 
  <!-- sharedData comes from UserController -->
  <!-- doctitle comes from UserController -->
    @include('profile-followers-only')


</x-profile>
