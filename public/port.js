// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    initializePortfolio();
    loadProjects(); // Load projects from database
});

function initializePortfolio() {
    // DOM Elements
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.section');
    const mainContent = document.getElementById('mainContent');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const contactForm = document.querySelector('.contact-form');
    const submitBtn = document.querySelector('.submit-btn');
    const profileDescription = document.querySelector('.profile-description');

    // Navigation functionality
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            navLinks.forEach(nl => nl.classList.remove('active'));
            link.classList.add('active');
            
            const targetId = link.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection && mainContent) {
                mainContent.scrollTo({
                    top: targetSection.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Update active navigation based on scroll position
    function updateActiveNavigation() {
        if (!mainContent || sections.length === 0) return;
        
        let currentSection = '';
        const scrollTop = mainContent.scrollTop;
        const viewportHeight = mainContent.clientHeight;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionBottom = sectionTop + sectionHeight;
            
            if (scrollTop >= sectionTop - viewportHeight/3 && scrollTop < sectionBottom - viewportHeight/3) {
                currentSection = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').substring(1) === currentSection) {
                link.classList.add('active');
            }
        });
    }

    // Contact form handling - Save to database
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';
            }
            
            const formData = new FormData();
            formData.append('name', contactForm.querySelector('input[placeholder="Your full name"]').value);
            formData.append('email', contactForm.querySelector('input[placeholder="your.email@example.com"]').value);
            formData.append('subject', contactForm.querySelector('input[placeholder="Project inquiry"]').value);
            formData.append('message', contactForm.querySelector('textarea').value);
            
            fetch('../src/save_messages.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result.trim() === 'success') {
                    showNotification('Message sent successfully!');
                    contactForm.reset();
                } else {
                    showNotification('Error sending message. Please try again.');
                }
            })
            .catch(error => {
                showNotification('Error sending message. Please try again.');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Message';
                }
            });
        });
    }

    // Typing animation for profile description
    if (profileDescription) {
        const originalText = profileDescription.textContent;
        profileDescription.textContent = '';
        
        let charIndex = 0;
        function typeText() {
            if (charIndex < originalText.length) {
                profileDescription.textContent += originalText.charAt(charIndex);
                charIndex++;
                setTimeout(typeText, 30);
            }
        }
        
        setTimeout(typeText, 1000);
    }

    // Add scroll listener for navigation highlighting
    if (mainContent) {
        mainContent.addEventListener('scroll', () => {
            updateActiveNavigation();
        });
    }

    // Initialize active navigation on page load
    updateActiveNavigation();

    setTimeout(() => {
        const activeNav = document.querySelector('.nav-link.active');
        if (!activeNav && navLinks.length > 0) {
            navLinks[0].classList.add('active');
        }
    }, 100);
}

// Load projects from database
function loadProjects() {
    const projectsGrid = document.getElementById('projectsGrid');
    if (!projectsGrid) return;

    fetch('../src/get_projects.php')
    .then(response => response.text())
    .then(html => {
        if (html.trim() === '') {
            projectsGrid.innerHTML = '<p>No projects found. Add some projects through the admin panel.</p>';
        } else {
            projectsGrid.innerHTML = html;
            setupProjectFilters(); // Setup filters after loading projects
        }
    })
    .catch(error => {
        projectsGrid.innerHTML = '<p>Error loading projects. Please check your database connection.</p>';
    });
}

// Setup project filtering after projects are loaded
function setupProjectFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            projectCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Add hover effects to project cards
    projectCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px)';
            card.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
}

// Custom notification function
function showNotification(message) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #4CAF50;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        z-index: 1000;
        font-family: Arial, sans-serif;
        transition: all 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Create background particles
function createParticle() {
    const particle = document.createElement('div');
    particle.className = 'particle';
    document.body.appendChild(particle);

    const size = Math.random() * 5 + 2;
    const startPos = Math.random() * window.innerWidth;
    
    particle.style.width = `${size}px`;
    particle.style.height = `${size}px`;
    particle.style.left = `${startPos}px`;
    particle.style.position = 'fixed';
    particle.style.background = 'rgba(255,255,255,0.5)';
    particle.style.borderRadius = '50%';
    particle.style.pointerEvents = 'none';
    particle.style.zIndex = '1';
    
    setTimeout(() => particle.remove(), 10000);
}

setInterval(createParticle, 3000);
