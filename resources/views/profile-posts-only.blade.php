<div class="list-group">
     @foreach ($posts as $post)
       <x-post :post="$post" hideAuthor /> <!-- load /views/components/post.blade -->
     @endforeach
 </div>
