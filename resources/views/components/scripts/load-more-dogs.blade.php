@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('load-more');
    const dogsList = document.getElementById('dogs-list');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            const nextPage = this.getAttribute('data-next-page');
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const queryString = new URLSearchParams(formData).toString();

            fetch(`{{ url()->current() }}?page=${nextPage}&${queryString}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;

                const newCards = tempDiv.querySelectorAll('.col-md-4');
                newCards.forEach(card => dogsList.appendChild(card));

                if (data.hasMore) {
                    loadMoreBtn.setAttribute('data-next-page', data.nextPage);
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            })
            .catch(error => console.error('خطا:', error));
        });
    }
});
</script>
@endpush
@endonce
