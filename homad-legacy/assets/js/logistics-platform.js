(function () {
    const tabs = document.querySelectorAll('.platform-tab');
    const panels = document.querySelectorAll('.platform-panel');

    if (!tabs.length || !panels.length) {
        return;
    }

    const setActivePanel = (targetId) => {
        tabs.forEach((tab) => tab.classList.remove('is-active'));
        panels.forEach((panel) => panel.classList.remove('is-active'));

        const activeTab = document.querySelector(`.platform-tab[data-platform-target="${targetId}"]`);
        const activePanel = document.getElementById(`platform-${targetId}`);

        if (activeTab) {
            activeTab.classList.add('is-active');
        }
        if (activePanel) {
            activePanel.classList.add('is-active');
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-platform-target');
            if (target) {
                setActivePanel(target);
            }
        });
    });
})();
