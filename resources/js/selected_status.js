export const initSelectedStatus = () => {
    const statusFilter = document.getElementById('status-filter');

    if (!statusFilter) return;

    statusFilter.addEventListener('change', () => {
        statusFilter.form.requestSubmit();
    });
};
