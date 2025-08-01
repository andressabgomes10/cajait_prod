// Mobile Menu Toggle - Versão Melhorada
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    
    if (mobileMenuBtn && mobileMenu) {
        let isMenuOpen = false;
        
        // Função para abrir menu
        function openMobileMenu() {
            mobileMenu.style.display = 'block';
            mobileMenu.style.opacity = '0';
            mobileMenu.style.transform = 'translateY(-10px)';
            
            requestAnimationFrame(() => {
                mobileMenu.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                mobileMenu.style.opacity = '1';
                mobileMenu.style.transform = 'translateY(0)';
            });
            
            // Animar hambúrguer
            const hamburgers = mobileMenuBtn.querySelectorAll('.hamburger');
            hamburgers[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
            hamburgers[1].style.opacity = '0';
            hamburgers[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            
            isMenuOpen = true;
        }
        
        // Função para fechar menu
        function closeMobileMenu() {
            mobileMenu.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            mobileMenu.style.opacity = '0';
            mobileMenu.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                mobileMenu.style.display = 'none';
            }, 300);
            
            // Resetar hambúrguer
            const hamburgers = mobileMenuBtn.querySelectorAll('.hamburger');
            hamburgers[0].style.transform = '';
            hamburgers[1].style.opacity = '1';
            hamburgers[2].style.transform = '';
            
            isMenuOpen = false;
        }
        
        // Toggle mobile menu
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (isMenuOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
        
        // Close mobile menu when clicking on links
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                closeMobileMenu();
            });
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (isMenuOpen && !mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                closeMobileMenu();
            }
        });
    }
});

// Smooth scrolling melhorado
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

// Active section highlighting aprimorado
function updateActiveSection() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    const scrollPosition = window.scrollY + 150;

    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');

        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
            navLinks.forEach(link => {
                link.classList.remove('nav-link-active');
            });

            const activeLink = document.querySelector(`a[href="#${sectionId}"]`);
            if (activeLink) {
                activeLink.classList.add('nav-link-active');
            }
        }
    });
}

// Scroll to top melhorado
function toggleScrollToTop() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    if (scrollToTopBtn) {
        if (window.scrollY > 300) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }
    }
}

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Performance monitoring
let ticking = false;
window.addEventListener('scroll', () => {
    if (!ticking) {
        requestAnimationFrame(() => {
            updateActiveSection();
            toggleScrollToTop();
            updateHeaderOnScroll();
            ticking = false;
        });
        ticking = true;
    }
});

// Form handling melhorado
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const formMessage = document.getElementById('formMessage');
            
            // Validação cliente
            const requiredFields = contactForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                field.classList.remove('error');
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                showFormMessage('error', '❌ Por favor, preencha todos os campos obrigatórios.');
                return;
            }
            
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitText.innerHTML = `
                <div class="loading-spinner"></div>
                Enviando...
            `;
            
            hideFormMessage();
            
            const formData = new FormData(this);
            
            try {
                const isLocal = window.location.hostname === 'localhost' || 
                               window.location.hostname === '127.0.0.1' || 
                               window.location.hostname === '';
                
                const contactEndpoint = isLocal ? 'contact_local.php' : 'contact.php';
                
                const response = await fetch(contactEndpoint, {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showFormMessage('success', result.message);
                    
                    // Reset form after delay
                    setTimeout(() => {
                        contactForm.reset();
                    }, 1000);
                    
                    // Auto-hide success message
                    setTimeout(() => {
                        hideFormMessage();
                    }, 8000);
                } else {
                    showFormMessage('error', result.message);
                }
            } catch (error) {
                console.error('Form error:', error);
                showFormMessage('error', '❌ Erro de conexão. Verifique sua internet e tente novamente.');
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
    }
});

function showFormMessage(type, message) {
    const formMessage = document.getElementById('formMessage');
    if (formMessage) {
        formMessage.className = `form-message ${type}`;
        
        const icon = type === 'success' 
            ? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/></svg>'
            : '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>';
        
        formMessage.innerHTML = `
            ${icon}
            <div>${message}</div>
        `;
        formMessage.style.display = 'flex';
    }
}

function hideFormMessage() {
    const formMessage = document.getElementById('formMessage');
    if (formMessage) {
        formMessage.style.display = 'none';
        formMessage.className = 'form-message';
    }
}

// Help Chat Widget melhorado
document.addEventListener('DOMContentLoaded', function() {
    const helpChatWidget = document.getElementById('helpChatWidget');
    const helpChatTrigger = document.getElementById('helpChatTrigger');
    const helpChatMenu = document.getElementById('helpChatMenu');
    const helpMenuClose = document.getElementById('helpMenuClose');
    
    if (!helpChatWidget || !helpChatTrigger || !helpChatMenu) {
        return;
    }
    
    helpChatWidget.style.display = 'block';
    helpChatWidget.style.visibility = 'visible';
    helpChatWidget.style.opacity = '1';
    
    let isMenuOpen = false;
    
    function openHelpMenu() {
        helpChatMenu.style.display = 'block';
        helpChatMenu.style.opacity = '0';
        helpChatMenu.style.transform = 'translateY(10px) scale(0.95)';
        
        requestAnimationFrame(() => {
            helpChatMenu.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            helpChatMenu.style.opacity = '1';
            helpChatMenu.style.transform = 'translateY(0) scale(1)';
        });
        
        isMenuOpen = true;
    }
    
    function closeHelpMenu() {
        helpChatMenu.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        helpChatMenu.style.opacity = '0';
        helpChatMenu.style.transform = 'translateY(10px) scale(0.95)';
        
        setTimeout(() => {
            helpChatMenu.style.display = 'none';
        }, 300);
        
        isMenuOpen = false;
    }
    
    helpChatTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (isMenuOpen) {
            closeHelpMenu();
        } else {
            openHelpMenu();
        }
    });
    
    if (helpMenuClose) {
        helpMenuClose.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeHelpMenu();
        });
    }
    
    document.addEventListener('click', function(event) {
        if (!helpChatWidget.contains(event.target) && isMenuOpen) {
            closeHelpMenu();
        }
    });
    
    const contactFormLink = helpChatMenu.querySelector('a[href="#contato"]');
    if (contactFormLink) {
        contactFormLink.addEventListener('click', function() {
            closeHelpMenu();
        });
    }
});

// Header scroll effect melhorado
function updateHeaderOnScroll() {
    const header = document.querySelector('.header');
    if (header) {
        if (window.scrollY > 50) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
            header.style.backdropFilter = 'blur(8px)';
        }
    }
}

// Intersection Observer para animações
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const fadeInObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
        }
    });
}, observerOptions);

// Inicializar animações
document.addEventListener('DOMContentLoaded', function() {
    // Elementos para animar
    const animateElements = document.querySelectorAll('.service-card, .benefit-card, .value-card, .team-member, .stat');
    
    animateElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        fadeInObserver.observe(el);
    });
    
    // Adicionar classe de animação
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #3E2F08;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .form-group input.error,
        .form-group textarea.error,
        .form-group select.error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
        }
        
        /* Animações melhoradas */
        .service-card:hover,
        .benefit-card:hover,
        .team-member:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
        }
        
        .btn-primary:hover,
        .btn-gold:hover {
            transform: translateY(-3px) scale(1.02) !important;
        }
        
        /* Melhor responsividade */
        @media (max-width: 768px) {
            .service-card:hover,
            .benefit-card:hover,
            .team-member:hover {
                transform: translateY(-4px) !important;
            }
        }
    `;
    document.head.appendChild(style);
});

// Lazy loading para imagens
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => {
        imageObserver.observe(img);
    });
});

// Performance e acessibilidade
document.addEventListener('DOMContentLoaded', function() {
    // Preload critical resources
    const criticalLinks = document.querySelectorAll('a[href^="#"]');
    criticalLinks.forEach(link => {
        link.setAttribute('rel', 'prefetch');
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const mobileMenu = document.getElementById('mobileMenu');
            const helpMenu = document.getElementById('helpChatMenu');
            
            if (mobileMenu && mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
            }
            
            if (helpMenu && helpMenu.style.display === 'block') {
                helpMenu.style.display = 'none';
            }
        }
    });
    
    // Focus management
    const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
    const modal = document.getElementById('helpChatMenu');
    
    if (modal) {
        modal.addEventListener('keydown', function(e) {
            const focusableContent = modal.querySelectorAll(focusableElements);
            const firstFocusableElement = focusableContent[0];
            const lastFocusableElement = focusableContent[focusableContent.length - 1];
            
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusableElement) {
                        lastFocusableElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusableElement) {
                        firstFocusableElement.focus();
                        e.preventDefault();
                    }
                }
            }
        });
    }
});

console.log('🚀 Site Cajá - Script melhorado carregado com sucesso!');