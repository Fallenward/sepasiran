<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.filter-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                document.getElementById('filterForm').submit();
            });
        });
    });
    </script>
