const tabGroups = Array.from(document.querySelectorAll('.trl-tab-switcher__group'));

const hasTabGroups = () => tabGroups.length;

window.addEventListener('load', () => {
  if (hasTabGroups()) {
    tabInit(tabGroups);
  }
});

const tabInit = (arr) => {
  tabHandler(arr);
};

const tabHandler = (arr) => {
  arr.forEach((group) => {
    const container = group.parentElement;
    const tabs      = Array.from(group.querySelectorAll('.trl-tab-switcher__item'));
    const panels    = Array.from(container.querySelectorAll('.trl-pricing'));

    // 初期状態: 最初のタブをアクティブに、2つ目以降のパネルを非表示
    if (tabs[0]) tabs[0].classList.add('active');
    panels.forEach((panel, i) => {
      if (i !== 0) panel.hidden = true;
    });

    tabs.forEach((tab, index) => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        panels.forEach(p => { p.hidden = true; });
        tab.classList.add('active');
        if (panels[index]) panels[index].hidden = false;
      });
    });
  });
};
