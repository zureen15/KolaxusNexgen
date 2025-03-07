
document.addEventListener('DOMContentLoaded', function () {
  const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');
  const menuBar = document.querySelector('#content nav .bx.bx-menu');
  const sidebar = document.getElementById('sidebar');
  const searchButton = document.querySelector('#content nav form .form-input button');
  const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
  const searchForm = document.querySelector('#content nav form');
  const switchMode = document.getElementById('switch-mode');

  // Function to toggle sidebar state and save it in localStorage
  const toggleSidebar = () => {
    sidebar.classList.toggle('hide');
    localStorage.setItem('sidebarState', sidebar.classList.contains('hide') ? 'hide' : 'show');
  };

  // Apply saved sidebar state on page load
  const applySavedSidebarState = () => {
    const savedSidebarState = localStorage.getItem('sidebarState');
    if (savedSidebarState === 'hide') {
      sidebar.classList.add('hide');
    } else {
      sidebar.classList.remove('hide');
    }
    // Remove the initial hidden class
    sidebar.classList.remove('sidebar-hidden');
  };

  // Apply saved sidebar state immediately
  applySavedSidebarState();

  // Add event listener to menu bar for toggling sidebar
  menuBar.addEventListener('click', toggleSidebar);

  // Add event listeners to side menu items for active class
  allSideMenu.forEach(item => {
    const li = item.parentElement;

    item.addEventListener('click', function () {
      allSideMenu.forEach(i => {
        i.parentElement.classList.remove('active');
      });
      li.classList.add('active');
    });
  });

  // Add event listener to search button for toggling search form
  searchButton.addEventListener('click', function (e) {
    if (window.innerWidth < 576) {
      e.preventDefault();
      searchForm.classList.toggle('show');
      if (searchForm.classList.contains('show')) {
        searchButtonIcon.classList.replace('bx-search', 'bx-x');
      } else {
        searchButtonIcon.classList.replace('bx-x', 'bx-search');
      }
    }
  });

  // Handle window resize events
  window.addEventListener('resize', function () {
    if (this.innerWidth > 576) {
      searchButtonIcon.classList.replace('bx-x', 'bx-search');
      searchForm.classList.remove('show');
    }
  });

  // Apply initial states based on window width
  if (window.innerWidth < 768) {
    sidebar.classList.add('hide');
  } else if (window.innerWidth > 576) {
    searchButtonIcon.classList.replace('bx-x', 'bx-search');
    searchForm.classList.remove('show');
  }

  // Add event listener to switch mode for dark mode toggle
  switchMode.addEventListener('change', function () {
    if (this.checked) {
      document.body.classList.add('dark');
      localStorage.setItem('mode', 'dark');
    } else {
      document.body.classList.remove('dark');
      localStorage.setItem('mode', 'light');
    }
  });

  // Apply saved mode on page load
  const currentMode = localStorage.getItem('mode');
  if (currentMode === 'dark') {
    document.body.classList.add('dark');
    switchMode.checked = true;
  }
});
