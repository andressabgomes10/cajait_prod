// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    
    // Toggle mobile menu
    mobileMenuBtn.addEventListener('click', function() {
        if (mobileMenu.style.display === 'block') {
            mobileMenu.style.display = 'none';
        } else {
            mobileMenu.style.display = 'block';
        }
    });
    
    // Close mobile menu when clicking on links
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.style.display = 'none';
        });
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.style.display = 'none';
        }
    });
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const headerHeight = 80;
            const targetPosition = target.offsetTop - headerHeight;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Active section highlighting
function updateActiveSection() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    const scrollPosition = window.scrollY + 100; // Offset for header

    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');

        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
            // Remove active class from all links
            navLinks.forEach(link => {
                link.classList.remove('nav-link-active');
            });

            // Add active class to current section link
            const activeLink = document.querySelector(`a[href="#${sectionId}"]`);
            if (activeLink) {
                activeLink.classList.add('nav-link-active');
            }
        }
    });
}

// Scroll to top button functionality
function toggleScrollToTop() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    if (window.scrollY > 300) {
        scrollToTopBtn.classList.add('show');
    } else {
        scrollToTopBtn.classList.remove('show');
    }
}

// Scroll to top function
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Add scroll event listeners
window.addEventListener('scroll', () => {
    updateActiveSection();
    toggleScrollToTop();
});

// Form handling
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const formMessage = document.getElementById('formMessage');
    
    // Disable button and show loading state
    submitBtn.disabled = true;
    submitText.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
            <path d="M21 12a9 9 0 11-6.219-8.56"/>
        </svg>
        Enviando...
    `;
    
    // Hide previous messages
    formMessage.style.display = 'none';
    formMessage.className = 'form-message';
    
    // Get form data
    const formData = new FormData(this);
    
    try {
        // Detectar se está rodando localmente
        const isLocal = window.location.hostname === 'localhost' || 
                       window.location.hostname === '127.0.0.1' || 
                       window.location.hostname === '';
        
        const contactEndpoint = isLocal ? 'contact_local.php' : 'contact.php';
        
        const response = await fetch(contactEndpoint, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Success
            formMessage.className = 'form-message success';
            formMessage.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22,4 12,14.01 9,11.01"/>
                </svg>
                <div>${result.message}</div>
            `;
            formMessage.style.display = 'flex';
            
            // Reset form after a delay
            setTimeout(() => {
                this.reset();
            }, 1000);
            
            // Auto-hide success message after 8 seconds
            setTimeout(() => {
                if (formMessage.classList.contains('success')) {
                    formMessage.style.opacity = '0';
                    setTimeout(() => {
                        formMessage.style.display = 'none';
                        formMessage.style.opacity = '1';
                    }, 300);
                }
            }, 8000);
        } else {
            // Error
            formMessage.className = 'form-message error';
            formMessage.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m15 9-6 6"/>
                    <path d="m9 9 6 6"/>
                </svg>
                <div>${result.message}</div>
            `;
            formMessage.style.display = 'flex';
        }
    } catch (error) {
        // Network error
        formMessage.className = 'form-message error';
        formMessage.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="m15 9-6 6"/>
                <path d="m9 9 6 6"/>
            </svg>
            <div>❌ Erro de conexão. Verifique sua internet e tente novamente.</div>
        `;
        formMessage.style.display = 'flex';
    }
    
    // Re-enable button
    submitBtn.disabled = false;
    submitText.innerHTML = `
        Enviar mensagem
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"/>
            <polygon points="22,2 15,22 11,13 2,9 22,2"/>
        </svg>
    `;
});

// Help Chat Widget Management - Versão 100% Funcional
document.addEventListener('DOMContentLoaded', function() {
    console.log('Iniciando widget de ajuda...');
    
    const helpChatWidget = document.getElementById('helpChatWidget');
    const helpChatTrigger = document.getElementById('helpChatTrigger');
    const helpChatMenu = document.getElementById('helpChatMenu');
    const helpMenuClose = document.getElementById('helpMenuClose');
    
    if (!helpChatWidget || !helpChatTrigger || !helpChatMenu) {
        console.error('Elementos do widget não encontrados');
        return;
    }
    
    console.log('Widget elementos encontrados, inicializando...');
    
    // Mostrar o widget imediatamente
    helpChatWidget.style.display = 'block';
    helpChatWidget.style.visibility = 'visible';
    helpChatWidget.style.opacity = '1';
    
    let isMenuOpen = false;
    
    // Função para abrir menu
    function openMenu() {
        console.log('Abrindo menu...');
        helpChatMenu.style.display = 'block';
        helpChatMenu.classList.add('show');
        isMenuOpen = true;
    }
    
    // Função para fechar menu
    function closeMenu() {
        console.log('Fechando menu...');
        helpChatMenu.style.display = 'none';
        helpChatMenu.classList.remove('show');
        isMenuOpen = false;
    }
    
    // Event listener para o botão principal
    helpChatTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Clique no trigger detectado');
        
        if (isMenuOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    });
    
    // Event listener para o botão de fechar
    if (helpMenuClose) {
        helpMenuClose.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Clique no close detectado');
            closeMenu();
        });
    }
    
    // Fechar menu quando clicar fora
    document.addEventListener('click', function(event) {
        if (!helpChatWidget.contains(event.target) && isMenuOpen) {
            console.log('Clique fora detectado, fechando menu');
            closeMenu();
        }
    });
    
    // Event listener para link de contato
    const contactFormLink = helpChatMenu.querySelector('a[href="#contato"]');
    if (contactFormLink) {
        contactFormLink.addEventListener('click', function() {
            console.log('Link de contato clicado');
            closeMenu();
        });
    }
    
    // Widget sempre visível - sem controle de scroll
    // O widget permanece fixo e visível durante toda a navegação
    helpChatWidget.style.opacity = '1';
    helpChatWidget.style.visibility = 'visible';
    
    // Widget sempre visível
    console.log('Widget sempre visível configurado');
    
    console.log('Widget de ajuda inicializado com sucesso!');
});

// WhatsApp Float Button Management (Legacy - keeping for compatibility)
document.addEventListener('DOMContentLoaded', function() {
    const whatsappFloat = document.getElementById('whatsappFloat');
    let isVisible = true;
    
    // Show/hide based on scroll position
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const contactSection = document.getElementById('contato');
        
        if (contactSection && whatsappFloat) {
            const contactPosition = contactSection.offsetTop;
            const windowHeight = window.innerHeight;
            
            // Hide when near contact section to avoid overlap
            if (scrollTop + windowHeight > contactPosition - 100) {
                if (isVisible) {
                    whatsappFloat.style.transform = 'translateY(100px)';
                    whatsappFloat.style.opacity = '0';
                    isVisible = false;
                }
            } else {
                if (!isVisible) {
                    whatsappFloat.style.transform = 'translateY(0)';
                    whatsappFloat.style.opacity = '1';
                    isVisible = true;
                }
            }
        }
    });
    
    // Add click tracking
    if (whatsappFloat) {
        whatsappFloat.addEventListener('click', function() {
            // Track WhatsApp button click (you can add analytics here)
            console.log('WhatsApp button clicked');
        });
        
        // Add entrance animation after page load
        setTimeout(() => {
            whatsappFloat.style.transform = 'translateY(0)';
            whatsappFloat.style.opacity = '1';
        }, 1500);
    }
});

// Add scroll effect to header
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 50) {
        header.style.background = 'rgba(255, 255, 255, 0.98)';
        header.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.background = 'rgba(255, 255, 255, 0.95)';
        header.style.boxShadow = 'none';
    }
});

// Add intersection observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', function() {
    const animateElements = document.querySelectorAll('.service-card, .benefit-card, .value-card, .stat');
    
    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});