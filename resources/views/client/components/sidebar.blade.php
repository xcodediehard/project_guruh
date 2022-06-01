<ul class="list-group">
    <li class="list-group-item bg-danger text-white " aria-current="true">Merek Sepatu</li>
    @foreach ($data["category_list"] as $category_item)
    <a href="{{ route('home_by_merek', ['search'=>$category_item->merek]) }}" class="text-dark">
        <li class="list-group-item text-dark">{{$category_item->merek}}</li>
    </a>
    @endforeach
</ul>