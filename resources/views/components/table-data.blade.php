<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="p-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-700">{{ $name }}</h3>
    </div>

    <div class="overflow-x-auto">
        <table id="dataTable" class="min-w-full divide-y divide-gray-200 border-separate">
            <thead class="bg-gray-50">
            <tr>
                {{ $column }}
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            {{ $row }}
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "paging": true,
                "pagingType": "simple",
                "lengthMenu": [5, 10, 25, 50],
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "language": {
                    "search": "Search Table:",
                    "lengthMenu": "Show _MENU_ items per page",
                    "zeroRecords": "No matching menu items found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ items",
                    "infoEmpty": "No items available",
                    "infoFiltered": "(filtered from _MAX_ total items)",
                    "pagination": {
                        "previous": "Previous",
                        "next": "Next"
                    }
                },
                "dom": '<"flex flex-col sm:flex-row justify-between items-center mb-4"l<"ml-2"f>>rt<"flex flex-col sm:flex-row justify-between items-center mt-4"ip>',
                "classes": {
                    "sLength": "relative inline-block",
                    "sFilter": "relative inline-block",
                    "sProcessing": "text-center py-4",
                    "sInfo": "text-sm text-gray-700",
                    "sPaging": "relative z-0 inline-flex shadow-sm rounded-md",
                    "paginate": {
                        "previous": "relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50",
                        "next": "relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50",
                        "first": "hidden",
                        "last": "hidden"
                    },
                    "sPageButton": "relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50",
                    "sPageButtonActive": "z-10 bg-blue-50 border-blue-500 text-blue-600",
                    "sPageButtonDisabled": "cursor-not-allowed opacity-50",
                    "sLengthSelect": "form-select rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50",
                    "sFilterInput": "form-input rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                }
            });
        });
    </script>
@endpush

{{ $scripting }}
