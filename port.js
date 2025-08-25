// DOM Elements
const navLinks = document.querySelectorAll('.nav-link');
const sections = document.querySelectorAll('.section');
const mainContent = document.getElementById('mainContent');
const filterButtons = document.querySelectorAll('.filter-btn');
const projectCards = document.querySelectorAll('.project-card');
const contactForm = document.querySelector('.contact-form');
const submitBtn = document.querySelector('.submit-btn');
const profileDescription = document.querySelector('.profile-description');

// Navigation and scroll functionality
navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);
        mainContent.scrollTo({
            top: targetSection.offsetTop,
            behavior: 'smooth'
        });
    });
});

// Update active navigation based on scroll position
function updateActiveNavigation() {
    let currentSection = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (mainContent.scrollTop >= sectionTop - 200) {
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

// Project filtering functionality
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

// Contact form handling
if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
        
        // Simulate form submission
        setTimeout(() => {
            contactForm.reset();
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send Message';
            alert('Message sent successfully!');
        }, 2000);
    });
}

// Add hover effects to project cards
projectCards.forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-10px)';
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
    });
});

// Typing animation for profile description
if (profileDescription) {
    const originalText = profileDescription.textContent;
    profileDescription.textContent = '';
    
    let charIndex = 0;
    function typeText() {
        if (charIndex < originalText.length) {
            profileDescription.textContent += originalText.charAt(charIndex);
            charIndex++;
            setTimeout(typeText, 50);
        }
    }
    
    // Start typing animation after a short delay
    setTimeout(typeText, 1000);
}

// Add parallax effect to sections
mainContent.addEventListener('scroll', () => {
    sections.forEach(section => {
        const speed = section.getAttribute('data-speed') || 0.5;
        const yPos = -(mainContent.scrollTop * speed);
        section.style.backgroundPositionY = yPos + 'px';
    });
});

// Intersection Observer for animations
const observerOptions = {
    root: mainContent,
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate');
        }
    });
}, observerOptions);

// Observe all sections for animations
sections.forEach(section => {
    observer.observe(section);
});

// Add smooth reveal animation for project cards
const projectObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }, index * 100);
        }
    });
}, { threshold: 0.1 });

projectCards.forEach(card => {
    projectObserver.observe(card);
});

// Create and handle dynamic background particles
function createParticle() {
    const particle = document.createElement('div');
    particle.className = 'particle';
    document.body.appendChild(particle);

    const size = Math.random() * 5 + 2;
    const startPos = Math.random() * window.innerWidth;
    
    particle.style.width = `${size}px`;
    particle.style.height = `${size}px`;
    particle.style.left = `${startPos}px`;
    
    setTimeout(() => particle.remove(), 10000);
}

// Listen for scroll events to update navigation
mainContent.addEventListener('scroll', updateActiveNavigation);

// Initialize active navigation on page load
updateActiveNavigation();

// Create particles periodically
setInterval(createParticle, 3000);
