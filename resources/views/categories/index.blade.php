<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nested Category Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        .list-group-item {
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
            border-radius: 0.25rem;
        }

        .subcategories-container {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <!-- Form to add a new category -->
    <div class="mb-3">
        <form action="{{ url('/categories/store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="category_name">New Category:</label>
                <input type="text" class="form-control" name="title" id="category_name" required placeholder="Main Category Name">
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </div>

    <!-- Category list -->
    <ul class="list-group">
        @foreach ($categories as $category)
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $category->title }}</span>

                    <div class="button-group">
                        <button type="button" class="btn btn-sm btn-primary add-sub-category" data-toggle="modal" data-target="#addCategoryModal" data-id="{{ $category->id }}">Add Subcategory</button>
                    </div>
                </div>

                <!-- Subcategories -->
                @if ($category->children)
                    <!-- Update the subcategory list structure -->
                    <ul class="list-group mt-2" id="sortable">
                        @foreach($category->children as $subcategory)
                            <li class="list-group-item" data-id="{{ $subcategory->id }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $subcategory->title }}</span>
                                    <div class="button-group">
                                        <button type="button" class="btn btn-sm btn-primary add-sub-category" data-toggle="modal" data-target="#addCategoryModal" data-id="{{ $subcategory->id }}">Add Subcategory</button>
                                    </div>
                                </div>
                                <!-- Subcategories -->
                                @if ($subcategory->children)
                                    <!-- Update the subcategory list structure -->
                                    <ul class="list-group mt-2" id="sortable">
                                        @foreach($subcategory->children as $subcategory0)
                                            <li class="list-group-item" data-id="{{ $subcategory0->id }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>{{ $subcategory0->title }}</span>
                                                    <div class="button-group">
                                                        <button type="button" class="btn btn-sm btn-primary add-sub-category" data-toggle="modal" data-target="#addCategoryModal" data-id="{{ $subcategory0->id }}">Add Subcategory</button>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- Subcategories -->
                                            @if ($subcategory0->children)
                                                <!-- Update the subcategory list structure -->
                                                <ul class="list-group mt-2" id="sortable">
                                                    @foreach($subcategory0->children as $subcategory1)
                                                        <li class="list-group-item" data-id="{{ $subcategory1->id }}">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span>{{ $subcategory1->title }}</span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>

    <!-- Add Subcategory Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Subcategory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/categories/store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="subcategory_name">New Subcategory:</label>
                            <input type="text" class="form-control" name="title" id="subcategory_name" required placeholder="Name">
                        </div>
                        <input type="hidden" name="cat_id" id="cat_id">
                        <button type="submit" class="btn btn-primary ">Add Subcategory</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#category-form').submit(function(event) {
            event.preventDefault();

            // Send AJAX request to add category
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Add a new input for subcategory
                    appendSubcategoryInput(response.id);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        // Handle click event for the "Add Sub Category" button
        $('.add-sub-category').on('click', function() {
            var categoryId = $(this).data('id');
            $("#cat_id").val(categoryId);
            $('#addCategoryModal').modal('show');
        });

        // Handle form submission for adding a new subcategory
        $(document).on('submit', '#addSubcategoryForm', function(e) {
            e.preventDefault();

            var parentId = $(this).data('parent-id');
            var subcategoryName = $('#subcategory_name').val();

            // AJAX request to add the subcategory
            $.ajax({
                type: 'POST',
                url: '{{ url("/categories/store") }}',
                data: { name: subcategoryName, parent_id: parentId },
                success: function(response) {
                    // Add the new subcategory dynamically to the list
                    $('.subcategories-container[data-parent-id="' + parentId + '"]').append(response);
                    $('#subcategory_name').val(''); // Clear the input field
                    $('#addCategoryModal').modal('hide'); // Hide the modal
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>

<!-- Add this script to enable drag-and-drop -->
<script>
    $(function() {
        $("#sortable").sortable({
            update: function(event, ui) {
                // Get the updated order and send it to the server
                var order = $(this).sortable('toArray', { attribute: 'data-id' });
                console.log(order);

                // Send the order to the server using AJAX
                // Example: You can use $.ajax to send the updated order to the server
                // $.ajax({
                //     url: '/updateSubcategoryOrder',
                //     method: 'POST',
                //     data: { order: order },
                //     success: function(response) {
                //         console.log(response);
                //     }
                // });
            }
        });

        $("#sortable").disableSelection();
    });
</script>

</body>
</html>
