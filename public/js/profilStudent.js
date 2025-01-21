
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('[data-category]');
        const courses = document.querySelectorAll('.bg-white.rounded-xl');
        const searchInput = document.getElementById('searchInput');

        //filter
        function filterCourses() {
            const searchTerm = searchInput.value.toLowerCase();
            const activeCategory = document.querySelector('[data-category].bg-red-500')?.getAttribute('data-category') || 'all';

            courses.forEach(course => {
                const title = course.querySelector('h3').textContent.toLowerCase();
                const description = course.querySelector('p').textContent.toLowerCase();
                const category = course.querySelector('p').textContent.toLowerCase();

                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                const matchesCategory = activeCategory === 'all' || category.includes(activeCategory);

                if (matchesSearch && matchesCategory) {
                    course.style.display = 'block';
                } else {
                    course.style.display = 'none';
                }
            });
        }

        // handle click
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => {
                    btn.classList.remove('bg-red-500', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });

                button.classList.remove('bg-gray-200', 'text-gray-700');
                button.classList.add('bg-red-500', 'text-white');

                filterCourses();
            });
        });

        // Gestion de la recherche
        searchInput.addEventListener('input', filterCourses);
    });
