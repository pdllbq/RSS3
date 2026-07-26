const feedList = document.querySelector('.feed-list');

if (feedList) {
    feedList.addEventListener('click', e => {
        const item = e.target.closest('.feed-item');

        if (!item || !feedList.contains(item) || e.target.closest('a')) {
            return;
        }

        window.open(item.dataset.url, '_blank');
    });
}