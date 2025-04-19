function generateSlug(titleId, slugId) {
    const titleElement = document.getElementById(titleId);
    const slugElement = document.getElementById(slugId);

    titleElement.addEventListener('input', function() {
        let title = this.value;
        // تبدیل عنوان به نامک
        let slug = title
            .toLowerCase() // حروف را به کوچک تبدیل می‌کند
            .replace(/[^\w\s-]/g, '') // حذف کاراکترهای غیرمجاز
            .replace(/\s+/g, '-') // جایگزینی فاصله‌ها با خط فاصله
            .replace(/-+/g, '-'); // حذف خطوط فاصله اضافی

        slugElement.value = slug;
    });
}
