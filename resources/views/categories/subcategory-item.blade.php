@foreach($subcategories as $subcategory)
    <li class="list-group-item">
        <div class="d-flex justify-content-between">
            {{ $subcategory->title }}

            <div class="button-group d-flex">
                <button type="button" class="btn btn-sm btn-primary mr-1 edit-category" data-toggle="modal" data-target="#editCategoryModal" data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->title }}">Add Sub Category</button>
            </div>
        </div>

        <!-- Recursive call to display subcategories -->
        <ul class="list-group mt-2">
            @include('categories.subcategory-item', ['subcategories' => $subcategory->children])
        </ul>
    </li>
@endforeach
