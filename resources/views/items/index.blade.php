<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel Excel Export</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 fw-bold">Data Management</h1>
            <div>
                <button class="btn btn-success" onclick="exportData()">Export to
                    Excel</button>
                <button onclick="openModal()" class="btn btn-primary">
                    Add New Data
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    <button onclick="deleteItem({{ $item->id }})"
                                        class="btn btn-link text-danger p-0">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dataForm">
                    <div class="modal-body">
                        <input type="hidden" id="itemId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" id="price" name="price" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="closeModal()" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('itemId').value = "";
            document.getElementById('name').value = "";
            document.getElementById('description').value = "";
            document.getElementById('price').value = "";
            const modal = new bootstrap.Modal(document.getElementById('dataModal'));
            modal.show();
        }

        function closeModal() {
            const modal = new bootstrap.Modal(document.getElementById('dataModal'));
            modal.hide();
        }

        function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                fetch(`/items/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload(); // Reload the page to reflect the changes
                    })
                    .catch(error => {
                        console.error('Error deleting item:', error);
                        alert('Failed to delete the item. Please try again.');
                    });
            }
        }

        document.getElementById('dataForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('itemId').value;
            const name = document.getElementById('name').value;
            const description = document.getElementById('description').value;
            const price = document.getElementById('price').value;
            const url = id ? `/items/${id}` : '/items';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        name,
                        description,
                        price
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    closeModal(); // Close the modal
                    location.reload(); // Reload the page to reflect the changes
                })
                .catch(error => {
                    console.error('Error saving data:', error);
                    alert('Failed to save the data. Please try again.');
                });
        });

        function exportData() {
            fetch('/export', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                })
                .then(response => {
                    if (response.ok) {
                        return response.blob(); // If the response contains a file
                    } else {
                        throw new Error('Failed to export data');
                    }
                })
                .then(blob => {
                    // Download the file
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'items.xlsx'; // Set the file name (it could be dynamic if needed)
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                })
                .catch(error => {
                    console.error('Export failed:', error);
                });
        }
    </script>
</body>

</html>
