<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const root = form.closest('.sp-filters') || document;
    const suggestUrl = (root.dataset && root.dataset.suggestUrl) || '';

    function debounce(fn, wait) {
        let t;
        return function () {
            const args = arguments;
            const ctx = this;
            clearTimeout(t);
            t = setTimeout(function () { fn.apply(ctx, args); }, wait);
        };
    }

    function submitFilters() {
        const pageInput = form.querySelector('input[name="page"]');
        if (pageInput) pageInput.remove();
        form.submit();
    }

    document.querySelectorAll('.filter-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            submitFilters();
        });
    });

    async function fetchSuggest(type, q) {
        if (!suggestUrl) return [];
        const url = suggestUrl + '?type=' + encodeURIComponent(type) + '&q=' + encodeURIComponent(q || '');
        const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return [];
        const data = await res.json();
        return data.results || [];
    }

    function localOptions(key, q) {
        const source = form.querySelector('[data-filter-options="' + key + '"]');
        if (!source) return [];
        let rows = [];
        try { rows = JSON.parse(source.textContent || '[]'); } catch (e) { rows = []; }
        const term = (q || '').trim();
        if (!term) return rows.slice(0, 8);
        return rows.filter(function (row) {
            return String(row.name || '').indexOf(term) !== -1;
        }).slice(0, 8);
    }

    function hidePanels(except) {
        document.querySelectorAll('[data-suggest-panel]').forEach(function (panel) {
            if (panel !== except) {
                panel.hidden = true;
                panel.innerHTML = '';
            }
        });
    }

    function renderPanel(panel, results, onPick) {
        if (!results.length) {
            panel.hidden = true;
            panel.innerHTML = '';
            return;
        }
        panel.innerHTML = results.map(function (item) {
            const meta = item.meta ? '<small>' + item.meta + '</small>' : '';
            return '<button type="button" class="sp-suggest__item" data-id="' + item.id + '" data-name="' + String(item.name).replace(/"/g, '&quot;') + '">' +
                '<span>' + item.name + '</span>' + meta +
                '</button>';
        }).join('');
        panel.hidden = false;
        panel.querySelectorAll('.sp-suggest__item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                onPick({ id: btn.dataset.id, name: btn.dataset.name });
                panel.hidden = true;
                panel.innerHTML = '';
            });
        });
    }

    const mainSearch = form.querySelector('input[name="q"]');
    if (mainSearch) {
        const panel = mainSearch.closest('.sp-filters__group')
            ? mainSearch.closest('.sp-filters__group').querySelector('[data-suggest-panel]')
            : null;
        const runSuggest = debounce(async function () {
            if (!panel || !mainSearch.dataset.suggestType || !suggestUrl) return;
            const q = mainSearch.value.trim();
            if (q.length < 1) { hidePanels(); return; }
            const results = await fetchSuggest(mainSearch.dataset.suggestType, q);
            hidePanels(panel);
            renderPanel(panel, results, function (item) {
                mainSearch.value = item.name;
                submitFilters();
            });
        }, 280);
        const runLiveSearch = debounce(function () {
            submitFilters();
        }, 550);
        mainSearch.addEventListener('input', function () {
            runSuggest();
            runLiveSearch();
        });
        mainSearch.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitFilters();
            }
        });
    }

    form.querySelectorAll('[data-typeahead]').forEach(function (wrap) {
        const key = wrap.getAttribute('data-typeahead');
        const input = wrap.querySelector('.sp-typeahead__input');
        const panel = wrap.querySelector('[data-suggest-panel]');
        const selected = wrap.querySelector('.sp-typeahead__selected');
        if (!input || !panel || !selected) return;

        const run = debounce(async function () {
            const q = input.value.trim();
            let results = [];
            if (suggestUrl) {
                results = await fetchSuggest(key, q);
            }
            if (!results.length) {
                results = localOptions(key, q);
            }
            hidePanels(panel);
            renderPanel(panel, results, function (item) {
                if (selected.querySelector('input[value="' + item.id + '"]')) {
                    input.value = '';
                    return;
                }
                const label = document.createElement('label');
                label.className = 'chip is-active sp-filter-chip';
                label.innerHTML = '<input type="checkbox" class="filter-checkbox" name="' + key + '[]" value="' + item.id + '" checked>' +
                    '<span>' + item.name + '</span>';
                selected.appendChild(label);
                label.querySelector('input').addEventListener('change', submitFilters);
                input.value = '';
                submitFilters();
            });
        }, 250);

        input.addEventListener('input', run);
        input.addEventListener('focus', run);
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.sp-typeahead') && !e.target.closest('.sp-search')) {
            hidePanels();
        }
    });
});
</script>