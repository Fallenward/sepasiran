<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('load-more');
    const dogsList = document.getElementById('dogs-list');
    const stateEl = document.getElementById('listing-state');

    function buildQuery(page) {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams();
        if (form) {
            new FormData(form).forEach(function (value, key) {
                if (value !== null && String(value).trim() !== '') {
                    params.append(key, value);
                }
            });
        }
        params.set('page', String(page));
        return params.toString();
    }

    function updateState(data) {
        if (!stateEl || !data) return;
        if (data.from && data.to) {
            stateEl.textContent = 'نمایش ' + data.from + ' تا ' + data.to + ' از ' + Number(data.total).toLocaleString('fa-IR') +
                ' آگهی · صفحه ' + data.currentPage + ' از ' + data.lastPage;
        }
    }

    function syncUrl(page) {
        const qs = buildQuery(page);
        const nextUrl = window.location.pathname + (qs ? ('?' + qs) : '');
        window.history.pushState({ page: page }, '', nextUrl);
    }

    if (loadMoreBtn && dogsList) {
        loadMoreBtn.addEventListener('click', function () {
            const nextPage = this.getAttribute('data-next-page');
            if (!nextPage) return;

            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'در حال بارگذاری…';

            fetch('{{ url()->current() }}?' + buildQuery(nextPage), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html || '';

                const newCards = tempDiv.querySelectorAll('.sp-listing-card, article.sp-card, .col-md-4');
                newCards.forEach(function (card) {
                    dogsList.appendChild(card);
                });

                updateState(data);
                syncUrl(data.currentPage || nextPage);

                if (data.hasMore) {
                    loadMoreBtn.setAttribute('data-next-page', data.nextPage);
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'بارگذاری بیشتر';
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            })
            .catch(function (error) {
                console.error('خطا:', error);
                loadMoreBtn.disabled = false;
                loadMoreBtn.textContent = 'بارگذاری بیشتر';
            });
        });
    }
});
</script>