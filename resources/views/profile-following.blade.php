<x-profile :sharedData="$sharedData" doctitle="Who {{$sharedData['username']}} follows"> 
  <!-- sharedData comes from UserController -->
  <!-- doctitle comes from UserController -->

    @include('profile-following-only')


</x-profile>
